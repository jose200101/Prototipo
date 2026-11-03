<?php
declare(strict_types=1); session_start(); require_once __DIR__ . '/includes/store.php';
$action = $_GET['action'] ?? ''; $input = request_json();
if ($action === 'products') json_response(['products'=>store_products()]);
if ($action === 'track') {
  $allowed = ['view','cart']; $type = clean_text($input['type'] ?? '', 12); if (!in_array($type,$allowed,true)) json_response(['ok'=>false],422);
  $events = read_json('events'); $events[] = ['type'=>$type,'product_id'=>clean_text($input['product_id']??'',40),'category'=>clean_text($input['category']??'',40),'visitor'=>client_hash(),'at'=>date(DATE_ATOM)];
  if(count($events)>20000)$events=array_slice($events,-20000); write_json('events',$events); json_response(['ok'=>true]);
}
if ($action === 'order') {
  $items = array_slice(is_array($input['items']??null)?$input['items']:[],0,30); $safe=[];
  foreach($items as $item)$safe[]=['id'=>clean_text($item['id']??'',40),'code'=>clean_text($item['code']??'',40),'name'=>clean_text($item['name']??'',100),'color'=>clean_text($item['color']??'',50),'size'=>clean_text($item['size']??'',30),'qty'=>max(1,min(20,(int)($item['qty']??1))),'price'=>(float)($item['price']??0)];
  $orders=read_json('orders');$orders[]=['id'=>'WA-'.date('ymd-His').'-'.random_int(100,999),'items'=>$safe,'total'=>(float)($input['total']??0),'visitor'=>client_hash(),'at'=>date(DATE_ATOM)];write_json('orders',$orders);json_response(['ok'=>true]);
}
if ($action === 'setup_status') json_response(['configured'=>is_file(data_file('admin'))]);
if ($action === 'setup') {
  if(is_file(data_file('admin')))json_response(['error'=>'El panel ya está configurado.'],409);$password=(string)($input['password']??'');if(strlen($password)<10)json_response(['error'=>'Usa al menos 10 caracteres.'],422);write_json('admin',['password'=>password_hash($password,PASSWORD_DEFAULT)]);$_SESSION['ann_admin']=true;json_response(['ok'=>true]);
}
if ($action === 'login') {
  $admin=read_json('admin');if(!isset($admin['password'])||!password_verify((string)($input['password']??''),$admin['password']))json_response(['error'=>'Contraseña incorrecta.'],401);$_SESSION['ann_admin']=true;json_response(['ok'=>true]);
}
if ($action === 'logout'){session_destroy();json_response(['ok'=>true]);}
if (empty($_SESSION['ann_admin'])) json_response(['error'=>'No autorizado'],401);
if ($action === 'dashboard') {
  $products=array_map('product_with_image',read_json('products'));$events=read_json('events');$orders=read_json('orders');$views=[];$carts=[];$cats=[];
  foreach($events as $e){$id=$e['product_id']??'';$cat=$e['category']??'Sin categoría';if(($e['type']??'')==='view'){$views[$id]=($views[$id]??0)+1;$cats[$cat]=($cats[$cat]??0)+1;}if(($e['type']??'')==='cart')$carts[$id]=($carts[$id]??0)+1;}
  $bought=[];foreach($orders as $o)foreach($o['items']??[] as $i)$bought[$i['id']]=($bought[$i['id']]??0)+(int)$i['qty'];
  $rank=array_map(function($p)use($views,$carts,$bought){return ['id'=>$p['id'],'name'=>$p['name'],'code'=>$p['code'],'category'=>$p['category'],'views'=>$views[$p['id']]??0,'carts'=>$carts[$p['id']]??0,'orders'=>$bought[$p['id']]??0];},$products);usort($rank,fn($a,$b)=>$b['views']<=>$a['views']);arsort($cats);
  $unique=count(array_unique(array_column($events,'visitor')));json_response(['products'=>$products,'metrics'=>['views'=>count(array_filter($events,fn($e)=>($e['type']??'')==='view')),'visitors'=>$unique,'carts'=>count(array_filter($events,fn($e)=>($e['type']??'')==='cart')),'orders'=>count($orders),'revenue'=>array_sum(array_column($orders,'total'))],'ranking'=>$rank,'categories'=>$cats,'recent_orders'=>array_slice(array_reverse($orders),0,8)]);
}
if ($action === 'save_product') {
  $products=read_json('products');$id=clean_text($input['id']??'',40)?:'ann-'.bin2hex(random_bytes(4));$image=clean_text($input['image_current']??'',240);
  if(isset($_FILES['image'])&&($_FILES['image']['error']??UPLOAD_ERR_NO_FILE)!==UPLOAD_ERR_NO_FILE){
    if($_FILES['image']['error']!==UPLOAD_ERR_OK)json_response(['error'=>'No se pudo cargar la imagen.'],422);
    if($_FILES['image']['size']>6*1024*1024)json_response(['error'=>'La imagen no debe superar 6 MB.'],422);
    $info=@getimagesize($_FILES['image']['tmp_name']);$types=['image/jpeg'=>'jpg','image/png'=>'png','image/webp'=>'webp'];$mime=$info['mime']??'';
    if(!isset($types[$mime]))json_response(['error'=>'Usa una imagen JPG, PNG o WebP.'],422);
    $uploadDir=__DIR__.'/assets/uploads';if(!is_dir($uploadDir))mkdir($uploadDir,0755,true);$filename=$id.'-'.bin2hex(random_bytes(5)).'.'.$types[$mime];
    if(!move_uploaded_file($_FILES['image']['tmp_name'],$uploadDir.'/'.$filename))json_response(['error'=>'No fue posible guardar la imagen.'],500);$image='assets/uploads/'.$filename;
  }
  $product=['id'=>$id,'code'=>strtoupper(clean_text($input['code']??'',30)),'name'=>clean_text($input['name']??'',100),'category'=>clean_text($input['category']??'',40),'price'=>max(0,(float)($input['price']??0)),'description'=>clean_text($input['description']??'',400),'colors'=>array_values(array_filter(array_map('trim',explode(',',clean_text($input['colors']??'',300))))),'sizes'=>array_values(array_filter(array_map('trim',explode(',',clean_text($input['sizes']??'',200))))),'tone'=>preg_match('/^#[0-9a-fA-F]{6}$/',(string)($input['tone']??''))?$input['tone']:'#c9b8aa','shape'=>clean_text($input['shape']??'bag',20),'featured'=>(int)($input['featured']??0),'image'=>$image,'active'=>true];
  $found=false;foreach($products as &$p)if(($p['id']??'')===$id){$p=$product;$found=true;break;}unset($p);if(!$found)$products[]=$product;write_json('products',$products);json_response(['ok'=>true,'id'=>$id]);
}
json_response(['error'=>'Acción no válida'],404);
