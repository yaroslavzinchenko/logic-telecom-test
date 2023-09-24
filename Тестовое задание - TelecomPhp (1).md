## Тестовое задание

Необходимо проанализировать и выполнить рефакторинг представленного фрагмента кода.
Готовое задание предоставить в виде репозитория на github/gitlab.

Представленный код необходимо привести в соответствие с современными практиками 
и нормами (желательно не используя фреймворки). В идеале покрыть отрефакторенный код тестами.

Можно делать любые допущения по логике кода, но описывать их в readme репозитория.

```php
<?php

/**
 * Upload docs
 */
$doc=new Documents;
// Return array of privs with values is bool
$privs=priv( "DOC_TREE_1,DOC_TREE_2,DOC_TREE_3,DOC_SEE_ALL" );

$info_str='';
$with_errs=false;

if( isset($_POST["operation"]) && isset($_POST["el_id"]) && isset($_POST["actor_id"]) && $privs['DOC_SEE_ALL']) {
	if( $_POST["operation"]=="check_attach" ) {
        $in=["did"=>$_POST["el_id"], "actor_id"=>$_POST["actor_id"]];
        $db=DB::obj();
        $res=$db->query("SELECT * from documents where us_id=:actor_id AND id=:did", $in);
		if(!$res) $info_str='Not attached'; else $info_str='attached';
	}
	elseif( $_POST["operation"]=="change_status" ) {
        $in=["did"=>$_POST["el_id"], "actor_id"=>$_POST["actor_id"], "status_id"=>$_POST["status_id"]];
        $res=DB::obj()->query("UPDATE documents set id=:did, status_id=:status_id, us_id=:actor_id", $in  );
		if(!$res) $info_str="Not CHANGED"; else $info_str='changed';
	}
}
if( isset($_POST["operation"]) && $_POST["operation"]=='upload_doc' ){
	if(!$_FILES['upload_doc']['tmp_name']) {$info_str='Выберите файл'; goto ex;}
	$data=array(
		'us_id'=>auth('us_id')
		,'doc_name'=>$_POST['upload_type']
		,'file_name'=>$_FILES['upload_doc']['name']
		,'doc_content'=>file_get_contents($_FILES['upload_doc']['tmp_name'])
	);
    $db=DB::obj();
    $in=["p_us_id"=>$data['us_id'], "p_doc_name"=>$data['doc_name'], "p_file_name"=>$data['file_name'], "data_BLOB"=>$data['doc_content']];
    $res=$db->query("INSERT INTO documents (us_id,doc_name,file_name,data_blob,status_id) VALUES (:p_us_id,:p_doc_name,:p_file_name,:data_BLOB,'new')", $in );
    if(!$res) $info_str='Error upload doc'; else $info_str='uploaded';
}
ex:
if($info_str!='') echo $info_str;

```

