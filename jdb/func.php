<?php
if(isset($_GET['url'])){
    
    $folderico="/resources/image/folderico.png";
    $docico="/resources/image/doc.png";
    $dir=urldecode($_GET['url']);
    if(stripos($dir,"..")!==false||(stripos(strtolower($dir),"./lib")!=0&&stripos(strtolower($dir),"./lib")!=2))exit();    //safety check
    $dir_list=scandir($dir);
    $colnum=0;
    $content_page='<br/><br/><br/><br/><br/>';
    // upper directory
    if(strtolower($dir)!='./lib')
    {
    $content_page.='<div class="row">';
    $folderurl='"'.pathinfo($dir)['dirname'].'"';
    $content_page.='
        <div class="col-sm-2 thumbnail">
        <img src="'.$folderico.'" class="img-rounded" onclick=\'return listdir('.$folderurl.')\'/>
        <br/>
        <h4>上级目录</h4>
        </div>';
    $colnum++;
    }

    
    foreach($dir_list as $file){
        if($file!='..' && $file!='.'){  
            // show directory content
            if(is_dir($dir.'/'.$file)){
                if($colnum%6==0)$content_page.='</div><div class="row">';
                $content_page.='<div class="col-sm-2 thumbnail">';
                $folderurl='"'.$dir.'/'.$file.'"';
                $content_page.='
                    <img src="'.$folderico.'" class="img-rounded" onclick=\'return listdir('.$folderurl.')\'/>
                    <br/>
                    <h4>'.$file.'</h4>
                    </div>';
                $colnum++;
            }else{
                $resurl=$dir.'/'.$file;
                $id=hash('md5',basename($resurl));
                if(isImage($resurl)){
                    if($colnum%6==0)$content_page.='</div><div class="row">';
                    $content_page.='
                    <div class="col-sm-2 thumbnail">
                        <img src="/jdb/'.$resurl.'" class="img-rounded " data-toggle="modal" data-target="#'.$id.'" />
                        <div class="modal fade" id="'.$id.'" role="dialog" aria-hidden="true">
                        <br/><br/><br/><br/><br/><br/><br/>
                            <div class="modal-dialog">
                            <div class="modal-content  col-sm-12">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times</button>
                                    <h4 class="modal-title">'.$file.'</h4>
                                </div>
                                <div class="modal-body  col-sm-12">
                                        <img src="/jdb/'.$resurl.'" class="img-rounded col-sm-8  col-sm-offset-2" />
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                    ';
                    $colnum++;
                }
                if(isMP4($resurl)){
                    if($colnum%6==0)$content_page.='</div><div class="row">';
                    $content_page.='
                    <div class="col-sm-2">
                        <video width="185" height="340" controls data-toggle="modal" data-target="#'.$id.'" >
                        <source src="/jdb/'.$resurl.'" type="video/mp4">
                        此浏览器不支持HTML 5 video 标签
                        </video>
                        <div class="modal fade" id="'.$id.'" role="dialog" aria-hidden="true">
                        <br/><br/><br/><br/><br/><br/><br/>
                            <div class="modal-dialog">
                            <div class="modal-content  col-sm-12">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times</button>
                                    <h4 class="modal-title">'.$file.'</h4>
                                </div>
                                <div class="modal-body  col-sm-12">
                                    <video controls class="col-sm-8  col-sm-offset-2" >
                                        <source src="/jdb/'.$resurl.'" type="video/mp4">
                                           此浏览器不支持HTML 5 video 标签
                                    </video>
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                    ';
                    $colnum++;
                }
                
                if(isDOC($resurl)||(isVideo($resurl)&&!isMP4($resurl))){
                    if($colnum%6==0)$content_page.='</div><div class="row">';
                    $content_page.='
                    <div class="col-sm-2 thumbnail">
                        <a href="/jdb/'.$resurl.'"><img src="'.$docico.'" class="img-rounded"></img></a>
                        <br/>
                        <h4>'.$file.'</h4>
                    </div>
                    ';
                    $colnum++;
                }
                
            }
        }
    }
    echo $content_page;
}



function isImage($filename){
    $a=pathinfo($filename);
    $ext=strtolower($a['extension']);
    if($ext=='jpg'||$ext=='png'||$ext=='gif'||$ext=='jpeg'||$ext=='bmp')return true;
    else return false;
}

function isVideo($filename){
    $a=pathinfo($filename);
    $ext=strtolower($a['extension']);
    if($ext=='mp4'||$ext=='vod'||$ext=='3gp'||$ext=='rm'||$ext=='rmvb')return true;
    else return false;
}

function isMP4($filename){
    $a=pathinfo($filename);
    $ext=strtolower($a['extension']);
    if($ext=='mp4')return true;
    else return false;
}

function isDOC($filename){
    $a=pathinfo($filename);
    $ext=strtolower($a['extension']);
    if($ext=='pdf'||$ext=='doc'||$ext=='docx'||$ext=='caj'||$ext=='txt')return true;
    else return false;
}


function get_all_files($dir){  
    $files=array();  
    $dir_list=scandir($dir);  
    foreach($dir_list as $file){  
        if($file!='..' && $file!='.'){  
            if(is_dir($dir.'/'.$file)){  
                $files[]=get_all_files($dir.'/'.$file);  
            }else{  
                $files[]=$dir.'/'.$file;  
            }  
        }  
    }  
    return $files;  
}  

?>
