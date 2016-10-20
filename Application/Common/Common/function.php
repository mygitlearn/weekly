<?php
/**
 * User: amose
 * Date: 2015/6/7
 * Time: 15:04
 */

/**
 * 获取当前时间的毫秒数
 * @return float
 */
function millisecond()
{
    return ceil(microtime(true) * 1000);
}

/**
 * 执行http get请求
 * @param $url
 * @param null $data
 * @return mixed
 */
function curl_get($url, $data = array())
{
    $data = array_merge($data, array('token' => $_COOKIE['token']));
    $data = http_build_query($data);
    $url .= '?' . $data;
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/A.B (KHTML, like Gecko) Chrome/X.Y.Z.W Safari/A.B.");
    $html = curl_exec($ch);
    curl_close($ch);
    return $html;
}

/**
 * 执行http post请求
 * @param $url
 * @param null $data
 * @return mixed
 */
function curl_post($url, $data = array())
{
    $data = array_merge($data, array('token' => $_COOKIE['token']));
    $post_data = http_build_query($data);
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/A.B (KHTML, like Gecko) Chrome/X.Y.Z.W Safari/A.B.");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $html = curl_exec($ch);

    curl_close($ch);

    return $html;
}

function resp()
{
    $varNum = func_num_args();
    $varArray = func_get_args();
    if ($varNum == 0) {
        //成功
        $res['code'] = 0;
    } else if ($varNum == 1) {
        $res['code'] = 0;
        $res['data'] = $varArray[0];
    } else {
        $res['code'] = intval($varArray[0]);
        $res['msg'] = $varArray[1];
    }
    echo json_encode($res);
    exit;
}

/**
 * 产生全局唯一ID
 * @return string
 */
function guid()
{
    mt_srand((double)microtime() * 10000);//optional for php 4.2.0 and up.
    $charid = strtoupper(md5(uniqid(rand(), true)));
    $uuid = substr($charid, 0, 8)
        . substr($charid, 8, 4)
        . substr($charid, 12, 4)
        . substr($charid, 16, 4)
        . substr($charid, 20, 12);
    return $uuid;
}

function To_Exel($fileName, $headArr, $list)
{
    //导入PHPExcel类库，因为PHPExcel没有用命名空间，只能import导入
    import("Org.Util.PHPExcel");
    import("Org.Util.PHPExcel.Writer.Excel5");
    import("Org.Util.PHPExcel.IOFactory.php");

    $date = date("Y_m_d", time());
    $fileName .= "_{$date}.xls";

    //创建PHPExcel对象，注意，不能少了\
    $objPHPExcel = new \PHPExcel();
    $objProps = $objPHPExcel->getProperties();

    //设置表头
    $key = ord("A");
    //print_r($headArr);exit;
    foreach ($headArr as $v) {
        $colum = chr($key);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum . '1', $v);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum . '1', $v);
        $key += 1;
    }

    $column = 2;
    $objActSheet = $objPHPExcel->getActiveSheet();
    foreach ($list as $key => $rows) { //行写入
        $span = ord("A");
        foreach ($rows as $keyName => $value) {// 列写入
            $j = chr($span);
            $objActSheet->setCellValue($j . $column, $value);
            $span++;
        }
        $column++;
    }

    $fileName = iconv("utf-8", "gb2312", $fileName);
    $objPHPExcel->setActiveSheetIndex(0);
    ob_end_clean();//清除缓冲区,避免乱码
    header('Content-Type: application/vnd.ms-excel');
    header("Content-Disposition: attachment;filename=\"$fileName\"");
    header('Cache-Control: max-age=0');

    $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    $objWriter->save('php://output'); //文件通过浏览器下载
    exit;
}

function From_Excel()
{
    $file = APP_PATH . "data.xls";
    import("Org.Util.PHPExcel");
    import("Org.Util.PHPExcel.Writer.Excel5");
    import("Org.Util.PHPExcel.IOFactory.php");

    $objReader = PHPExcel_IOFactory::createReader('Excel5');//use excel2007 for 2007 format
    $objPHPExcel = $objReader->load($file);//$file_url即Excel文件的路径
    $sheet = $objPHPExcel->getSheet(0);//获取第一个工作表
    $highestRow = $sheet->getHighestRow();//取得总行数
    $highestColumn = $sheet->getHighestColumn(); //取得总列数
    //循环读取excel文件,读取一条,插入一条
    $rows = array();
    for ($j = 1; $j <= $highestRow; $j++) {//从第一行开始读取数据
        $cells = '';
        for ($k = 'A'; $k <= $highestColumn; $k++) {            //从A列读取数据
            //这种方法简单，但有不妥，以'\\'合并为数组，再分割\\为字段值插入到数据库,实测在excel中，如果某单元格的值包含了\\导入的数据会为空
            $cell = $objPHPExcel->getActiveSheet()->getCell("$k$j")->getValue();
            if ($k == 'A') {
                $cell = gmdate("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($cell));
            }
            if ($k == "L") {
                $cell = ($cell * 100) . "%";
            }
            $cells .= $cell . '\\';//读取单元格
        }
        //explode:函数把字符串分割为数组。
        array_push($rows, explode("\\", $cells));
    }
    return $rows;
}


/*
 * 发送邮件
 * @param $to string
 * @param $title string
 * @param $content string
 * @return bool
 * */
function sendMail($to, $name, $title, $content)
{
    Vendor('PHPMailer.PHPMailerAutoload');
    $mail = new PHPMailer(); //实例化
    $mail->IsSMTP(); // 启用SMTP
    $mail->Host = C('MAIL_HOST'); //smtp服务器的名称（这里以QQ邮箱为例）
    $mail->SMTPAuth = C('MAIL_SMTPAUTH'); //启用smtp认证
    $mail->Username = C('MAIL_USERNAME'); //发件人邮箱名
    $mail->Password = C('MAIL_PASSWORD'); //163邮箱发件人授权密码
    $mail->From = C('MAIL_USERNAME'); //发件人地址（也就是你的邮箱地址）
    $mail->FromName = C('MAIL_FROMNAME'); //发件人姓名
    $mail->AddAddress($to, $name);
    $mail->WordWrap = 50; //设置每行字符长度
    $mail->IsHTML(C('MAIL_ISHTML')); // 是否HTML格式邮件
    $mail->CharSet = C('MAIL_CHARSET'); //设置邮件编码
    $mail->Subject = $title; //邮件主题
    $mail->Body = $content; //邮件内容
    $mail->AltBody = "这是一个纯文本的身体在非营利的HTML电子邮件客户端"; //邮件正文不支持HTML的备用显示
    return ($mail->Send());
}

