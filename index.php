<?php


/**
 * 创建缩略图
 * @param  [type]  $file_path  [图片路径]
 * @param  integer $max_width  [缩略图最大width，为0则表示和原图一致]
 * @param  integer $max_height [缩略图最大height，为0则表示和原图一致]
 * @param  integer $quality    [图片质量 10-100之间能被10整除的数]
 * @param  string  $suffix     [名称后缀]
 * @return [type]              [description]
 */
function thum($file_path,$max_width=0,$max_height=0,$quality = 100,$suffix='bak'){
        list($img_height,$img_width,$img_extension) = getimagesize($file_path);  
        $max_width == 0     && $max_width = $img_width;
        $max_height == 0    && $max_height = $img_height;  
        $new_img_size=get_thum_size($img_width,$img_height,$img_width,$img_height); 
          
        $img_func='';  
        $img_handle='';  
        $thum_handle=''; 
        switch($img_extension){
            case 1:  
                $img_handle=imageCreateFromGif($file_path);  
                $img_func='imagegif';  
                break;    
            case 2:  
                $img_handle=imageCreateFromJpeg($file_path);  
                $img_func='imagejpeg';  
                break; 
            case 3:  
                $img_handle=imagecreatefrompng($file_path);  
                echo 234234;
                $img_func='imagepng';  
                break;
            case 6:
                $img_handle=imageCreateFromBmp($file_path);  
                $img_func='imagejpeg';
                break;
            default:  
                $img_handle=imagecreatefromjpeg($file_path);  
                $img_func='imagejpeg';  
                break;  
        }
        /** 图片质量设置 */
        $quality=$quality; 
        if($img_func==='imagepng' && (str_replace('.', '', PHP_VERSION)>= 512)){ 
            $quality=ceil($quality/10);  
        }   

        /** 创建新图 */
        $thum_handle=imagecreatetruecolor($new_img_size['height'],$new_img_size['width']);  
        if(function_exists('imagecopyresampled')){  
            imagecopyresampled($thum_handle,$img_handle, 0, 0, 0, 0,$new_img_size['height'],$new_img_size['width'],$img_height,$img_width);  
            }else{  
                imagecopyresized($thum_handle,$img_handle, 0, 0, 0, 0,$new_img_size['height'],$new_img_size['width'],$img_height,$img_width);  
        }  
        call_user_func_array($img_func,array($thum_handle,get_thum_name($file_path,$suffix),$quality));  
        imagedestroy($thum_handle);  
        imagedestroy($img_handle);
    }  
  

function get_thum_size($width,$height,$max_width,$max_height){  
    $now_width=$width;
    $now_height=$height;  
    $size=array();  
    if($now_width>$max_width){  
        $now_height*=number_format($max_width/$width,4);  
        $now_width= $max_width;  
    }  

    if($now_height>$max_height){  
        $now_width*=number_format($max_height/$now_height,4);  
        $now_height=$max_height;  
    }  
    $size['width']=floor($now_width);  
    $size['height']=floor($now_height);  
    return $size;  
}  
  

function get_thum_name($file_path,$suffix){  
    $pathinfo = pathinfo($file_path);
    return $pathinfo['filename'].'_'.$suffix.'.'.$pathinfo['extension'];
}  

