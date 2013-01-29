<?php
	$curl = curl_init('http://219.143.230.144:7001/ifp-256/SyncInterface');
	//$curl = curl_init('http://1.cms.test/test.php');
	$signSeed = "signlyygtest";
	$data = '<?xml version="1.0" encoding="GBK"?>
<INSURENCEINFO>
	<USERNAME>lyygusername380gsntest</USERNAME>
	<PASSWORD>lyygpasswordnnsetest</PASSWORD>
	<ORDER>
    	<ORDERID>0001</ORDERID>
		<POLICYINFO NUM="1">
			<PRODUCTCODE>PP001201</PRODUCTCODE>
			<PLANCODE>PP001201</PLANCODE>
			<INSURDATE>2013-01-25</INSURDATE>
			<INSURSTARTDATE>2013-01-25</INSURSTARTDATE>
			<INSURENDDATE>2013-01-25</INSURENDDATE>
			<INSURPERIOD>1</INSURPERIOD>
			<PERIODFLAG>D</PERIODFLAG>
			
			<MULT>1</MULT>
			<AGREEMENTNO>860720130124</AGREEMENTNO>
			<PREMIUM>20.00</PREMIUM>  		
			<AMOUNT>20000.00</AMOUNT>
			<PROVINCE>湖北</PROVINCE>
			<REGION>武汉</REGION>
			<BENEFMODE>123</BENEFMODE>
			<OPERATOR>123</OPERATOR>
			
			<TRAINNO>Z68</TRAINNO>
			<TRAINDEPARTTIME>2013-02-25 14:50:00</TRAINDEPARTTIME>
			<TRAINARRITIME>2013-02-25 20:50:00</TRAINARRITIME>
			<TRAINORIGIN>武汉</TRAINORIGIN>
			<TRAINDESTINATION>上海</TRAINDESTINATION>

			
			<APPNTNAME>张三</APPNTNAME>
			<APPNTSEX>男</APPNTSEX>
			<APPNTBIRTHDAY>1980-01-01</APPNTBIRTHDAY>
			<APPNTIDTYPE>身份证</APPNTIDTYPE>
			<APPNTIDNO>420106198201013349</APPNTIDNO>
			<APPNTEMAIL></APPNTEMAIL>
			<APPNTMOBILE>18627873333</APPNTMOBILE>
			

			<INSUREDLIST>
				<INSURED>
					<INSUREDNAME>张三</INSUREDNAME>
					<RELATIONSHIP>本人</RELATIONSHIP>
					<INSUREDBIRTHDAY>1980-01-01</INSUREDBIRTHDAY>
					<INSUREDIDTYPE>身份证</INSUREDIDTYPE>
					<INSUREDIDNO>420106198201013349</INSUREDIDNO>
					<INSUREDEMAIL></INSUREDEMAIL>
					<INSUREDMOBILE>18627873333</INSUREDMOBILE>
				</INSURED>
			</INSUREDLIST>
		</POLICYINFO>
	</ORDER>
</INSURENCEINFO>';
	$sign = md5($signSeed.$data);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	//curl_setopt($curl, CURLOPT_HTTPHEADER, array(
	//	"Content-Type: multipart/form-data"
	//));
	curl_setopt($curl, CURLOPT_POST, true);
	curl_setopt($curl, CURLOPT_POSTFIELDS, 'data='.$data.'&sign='.$sign.'&functionFlag=INSURE&interfaceFlag=LYYG');
	
	/* array(
		'data='.$data.'&sign='.$sign.'&functionFlag=UNDERWRITE&interfaceFlag=LYYG',
		'sign' 			=> $sign,
		'functionFlag'	=> "UNDERWRITE",
		'interfaceFlag'	=> "LYYG"
		
	) */
	
//	);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	
	$body = curl_exec($curl);
	$ret = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	
	print_r($ret);
	echo "<br />========================<br />";
	print_r($body);
?>