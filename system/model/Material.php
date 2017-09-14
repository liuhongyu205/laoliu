<?php
namespace system\model;
use houdunwang\model\Model;
use Upload\Storage\FileSystem;
use Upload\File;
use Upload\Validation\Size;
class Material extends Model
{
    public function add()
    {
//      验证文件是否符合要求
        $file=current($_FILES);
//        当error为4时提示报错为文件没有上传
        if($file['error']==4) return['code'=>0,'msg'=>'没有文件上传'];
        //dd($res);
        $res=$this->upload();
        if(!$res['code']) return ['code'=>0,'msg'=>$res['msg'][0]];
        //执行添加数据库
        $data = [
            'mpath'=>$res['path'],
            'mtime'=>time (),
        ];
        $this->insert($data);
        return ['code'=>1,'msg'=>'添加成功'];
    }
    public function upload(){
        $dir = "uploads/" . date ('y/m/d');
        //目录创建
        is_dir ($dir) || mkdir ($dir,0777,true);
        $storage = new FileSystem($dir);
        $file = new File('mpath', $storage);
        $new_filename = uniqid();
        $file->setName($new_filename);
        $file->addValidations(array(
            // Ensure file is of type "image/png"


            //You can also add multi mimetype validation
            //new \Upload\Validation\Mimetype(array('image/png', 'image/gif'))

            // Ensure file is no larger than 5M (use "B", "K", M", or "G")
            new Size('5M')
        ));
        $data = array(
            'name'       => $file->getNameWithExtension(),

        );
        try {
            // Success!
            $file->upload();
            //将路径返回去
            return ['code'=>1,'msg'=>'','path'=>$dir . '/' . $data['name']];
        } catch (\Exception $e) {
            // Fail!
            $errors = $file->getErrors();
            return ['code'=>0,'msg'=>$errors];
        }
    }
}