<?php
namespace Home\Model;
use Think\Model;

class ProductsModel extends Model{

    public function edit($data){
        $data = array();
        $user_data = array();
        $project_info = M('project_info');
        $user_info = M('user_info');
        $add_to_history = M('project_history');
        $data_dict = M('data_dict');
        $id['Id']=$data['Id'];
        $data_list = $add_to_history->where("Id = id")->select();
        $arr['create_id']=$data['create_id'];
        $arr['dict_qy']=$data_qy=(int)$data['dict_qy'];
        //$get_name_qy =$data_dict->where("Id=$data_qy")->getField('name');

        $arr['dict_hy']=$data_hy=(int)$data['dict_hy'] ;
        //$get_name_hy =$data_dict->where("Id=$data_hy")->getField('name');

        $arr['dict_xs']=(int)$data['dict_xs'];
        //$get_user_name = $user_info->where("Id=$data_dict")->getField('name');

        $arr['name']=$data['name'];
       // $project_name=$project_info->where("Id=$id")->getField('name');

        $arr['channel']=$data['channel'];

        $arr['dict_cp']=(int)$data['dict_cp'];

        $arr['budget']=$data['budget'];

        $arr['tender_time']=$data['tender_time'] ;

        $arr['project_status']=(int)$data['project_status'] ;

        $arr['power']=(int)$data['power'];

        $arr['project_rate']=(int)$data['project_rate'] ;

        $arr['win_channel']=$data['win_channel'];

        $arr['vender']=$data['vender'];
        $content = '';
        foreach($data_list as $item)
        {
            if($item['dict_qy']!=$arr['dict_qy']){
                $content.="项目区域变换为".$arr['dict_qy'];
            }
            if($item['dict_hy']!=$arr['dict_hy']){
                $content.="项目的行业变换为：".$arr['dict_hy'];
            }
            if($item['dict_xs']!=$arr['dict_xs']){
                $content.="项目销售变换为".$arr['dict_xs'];
            }
            if($item['name']!=$arr['name']){
                $content.="项目名称变换为".$arr['name'];
            }
            if($item['channel']!=$arr['channel']){
                $content.="项目渠道信息变换为".$arr['name'];
            }
            if($item['dict_cp']!=$arr['dict_cp']){
                $content.="项目产品信息变换为".$arr['dict_cp'];
            }
            if($item['budget']!=$arr['budget']){
                $content.="项目的预算变换为".$arr['budget'];
            }
            if($item['tender_time']!=$arr['tender_time']){
                $content.="项目的投标时间变换为".$arr['tender_time'];
            }
            if($item['project_status']!=$arr['project_status']){
                $content.="项目的项目状态变换为".$arr['project_status'];
            }
            if($item['power']!=$arr['power']){
                $content.="项目的把握度变换为".$arr['power'];
            }
            if($item['project_rate']!=$arr['project_rate']){
                $content.="项目的当前进度变换为".$arr['project_rate'];
            }
            if($item['win_channel']!=$arr['win_channel']){
                $content.="项目的中标渠道变换为".$arr['win_channel'];
            }
            if($item['vender']!=$arr['vender']){
                $content.="项目的厂商负责人变换为".$arr['vender'];
            }
        }

        $data['project_id']=$id;
        $data['user_id'] = $arr['create_id'];
        $data['update_time']=time();
        $data['content'] = $content;
        $obj = $project_info->where($id)->data($arr)->save();
        if ($obj){
            return true;
        }else{
            return false;
        }
}



}


?>