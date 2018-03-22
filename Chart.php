<?php
namespace  ptrnov\fusionchart;

use Yii;
use yii\helpers\Json;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use yii\base\Widget;
use yii\helpers\Html;
use yii\web\View;
use yii\base\Exception;
   
/**
 * This is Chart Class 
 * Author by Piter Novian [ptr.nov@gmail.com]
*/
class Chart extends Widget
{
	//title
	const CHAT_CAPTION = 'caption';
	const CHAT_SUBCAPTION = 'subCaption';
	const CHAT_XAXISNAME = 'xAxisName';
	const CHAT_YAXISNAME = 'yAxisName';
	const CHAT_THEME = 'theme';
	
	//demensi	
	const CHAT_IS2D = 'is2D';							//boolean
	
	//label value
	const CHAT_SHOWLEGEND = 'showLegend'; 				//boolean
	const CHAT_SHOWVALUES = 'showValues'; 				//boolean
	const CHAT_SHOWPERCENTVALUES = 'showPercentValues';	//boolean
		
	//warna	
	const CHAT_PALETTECOLORS = 'palettecolors';
	const CHAT_BGCOLOR = 'bgColor';
	
	//border
	const CHAT_SHOWBORDER = 'showBorder';	
	const CHAT_SHOWCANVASBORDER = 'showCanvasBorder';	
	
	//aligin Caption
	const CHAT_ALIGNCAPTIONWITHCANVAS = 'alignCaptionWithCanvas';	
	
	public $urlSource='';
	public $metode='GET';			//new
	public $param=[];				//new
	public $autoResize=false;		//new
	public $dataArray='';
	public $dataField='';
	public $type='';
	public $renderid='';
	public $chartOption='';
	public $width=500;
	public $height=400;
	public $userid='';
	public $autoRender=true;
	
	public function run()	{		
		$html='<div id="'.$this->renderid.'"></div>';
		echo $html;
		$this->registerClientScript($this->renderid);
		
		//print_r(self::chartOption($this->chartOption));
		//print_r(self::setProvider($this->dataArray,$this->dataField));
		//print_r(self::_arraySource());
	}	
	
	 /**
     * @var array $dataModel, data value chart
	 * field['label','value'], normaly value is numeric
     * Defaults to `array`.
	 * Convert Array to Json 
	 * Author by Piter Novian [ptr.nov@gmail.com]
     */
	private static function setProvider($aryModel=[],$field=[]){
		if (!is_array($aryModel) or !is_array($field)) {
			$dataContent='';
		}else{
			//error hendling is not array
			if (!is_array($aryModel)) {
				 throw new Exception("Invalid dataArray, please source array 'dataArray'=>[array]");
			}
			//error hendling is not array
			if (!is_array($field)) {
				 throw new Exception("Invalid dataField, check your array 'dataField'=>['fieldName1','fieldName2']");
			}
			$dataProvider= new ArrayDataProvider([
				'allModels'=>$aryModel,
				// 'allModels'=>\Yii::$app->db->createCommand("	
					// SELECT id, username FROM userss
				// ")->queryAll(), 
			]);	
			$dataProviderModel=$dataProvider->getModels();
			foreach($dataProviderModel as $key => $value){
				//error hendling is offset
				if (isset($value[$field[0]]) && isset($value[$field[1]])){
					$dataContent[]=["label"=>$value[$field[0]],"value"=>$value[$field[1]]];
				}else{
					//return '[]';
					 throw new Exception("Invalid dataField, please check Column Name 'dataField'=>['wrongName','trueName']");
				}
				//$dataContent
			}; 		
			
		}
		return Json::encode($dataContent);
	}
		
	 /**
     * @var array $chartOption, data properties chart
	 *  Author by Piter Novian [ptr.nov@gmail.com]
     */
	private static function chartOption($opt){		
		if (!is_array($opt)) {
			$chat_opt='';
        };
		if (is_array($opt)) { 
			$chat_opt=[];
			foreach ($opt as $key => $vales){
				$keyLabel=strtoupper($key);
				if ($keyLabel=='CAPTION'){
					$chat_header = [self::CHAT_CAPTION =>$vales];
				}elseif ($keyLabel=='SUBCAPTION'){
					$chat_header = [self::CHAT_SUBCAPTION=>$vales];
				}elseif ($keyLabel=='XAXISNAME'){
					$chat_header = [self::CHAT_XAXISNAME=>$vales];
				}elseif ($keyLabel=='YAXISNAME'){
					$chat_header = [self::CHAT_YAXISNAME=>$vales];
				}elseif ($keyLabel=='THEME'){
					$chat_header = [self::CHAT_THEME=>$vales];
				}elseif ($keyLabel=='PALETTECOLORS'){
					$chat_header = [self::CHAT_PALETTECOLORS=>$vales];
				}elseif ($keyLabel=='BGCOLOR'){
					$chat_header = [self::CHAT_BGCOLOR=>$vales];
				}elseif ($keyLabel=='SHOWBORDER'){
					$chat_header = [self::CHAT_SHOWBORDER=>$vales];
				}elseif ($keyLabel=='SHOWCANVASBORDER'){
					$chat_header = [self::CHAT_SHOWCANVASBORDER=>$vales];
				}elseif ($keyLabel=='SHOWLEGEND'){
					$chat_header = [self::CHAT_SHOWLEGEND=>$vales];
				}elseif ($keyLabel=='SHOWVALUES'){
					$chat_header = [self::CHAT_SHOWVALUES=>$vales];
				}elseif ($keyLabel=='SHOWPERCENTVALUES'){
					$chat_header = [self::CHAT_SHOWPERCENTVALUES=>$vales];
				}elseif ($keyLabel=='IS2D'){
					$chat_header = [self::CHAT_IS2D=>$vales];
				}elseif ($keyLabel=='ALIGNCAPTIONWITHCANVAS'){
					$chat_header = [self::CHAT_ALIGNCAPTIONWITHCANVAS=>$vales];
				}else{
					$chat_header=[''];
				}				
				$chat_opt= array_merge($chat_opt,$chat_header);
			}
		}		
		return Json::encode($chat_opt);
	}
	private function _arrayChartOption(){
		if(isset($this->chartOption)){
			$chartOpt='
				"chart": '.self::chartOption($this->chartOption).'
			';
		}else{
			$chartOpt='[]';
		}
		return $chartOpt;
	}
	
	private function _arrayData(){
		if(isset($this->dataArray) AND isset($this->dataField)){
			$chartdata='
				"data": '.self::setProvider($this->dataArray,$this->dataField).'	
			';
		}else{
			$chartdata='[]';
		};
		return $chartdata;
	}
	
	private function _arraySource(){
		$array_pilot='{'
			.self::_arrayChartOption().
			','.self::_arrayData().			
		'}';
		//return  Json::decode($array_pilot);
		return  $array_pilot;		
		//"dataSource": "'.self::_arraySource().'"
		// "dataSource": {
						// "chart":'.self::chartOption($this->chartOption).',					 
						// "data": '.self::setProvider($this->dataArray,$this->dataField).'	   
					// }
						
	}
	/**
	* @var id,html id for render
	* Build script chart js
	*/
	public function registerClientScript($id)
    {
		if(!$this->urlSource){
			$script = '
				$(document).ready(function () {
				 var revenueChart = new FusionCharts({
						"type": "'.$this->type.'",					
						"renderAt": "'.$id.'",
						"width": "'.$this->width.'",	
						"height":"'.$this->height.'",	
						"dataFormat": "json",
						"dataSource": '.self::_arraySource().'
					});
					revenueChart.render();
				});
			';		
		}else{
				if($this->autoRender){
					$renderSetting='
						$(document).ready(function () {
							//handling sppiner load page.
							setTimeout(function(){
								 revenueChart.render();
							},1);						
						});
					';
				}else{
					$renderSetting='';
				}
			
			
			//if ($id && $this->type){
				//data:"id_user='.$this->userid.'",
				$script = '
						$(document).ready(function () {
							var myJsonString = '.json_encode($this->param).';
								//console.log(myJsonString);
							var  jsonData= $.ajax({
								  url: "'.$this->urlSource.'",
								  type: "'.$this->metode.'",
								  data: myJsonString,
								  dataType:"json",
								  async: false,
								  global: false
							}).responseText;		  
							var myDataChart= jsonData;
							//	console.log(myDataChart);						
							
								 var revenueChart = new FusionCharts({
									"type": "'.$this->type.'",					
									"renderAt": "'.$id.'",
									"width": "'.$this->width.'",	
									"height":"'.$this->height.'",	
									"dataFormat": "json",
									"dataSource": myDataChart,
									"events": {
										/**
										 * Add	  : Spinner loading.
										 * Author : ptr.nov@gmail.com.
										 * Update : 25/01/2017
										 */
										"renderComplete": function (eventObj, dataObj) {
											document.getElementById("loaderPtr").style.display = "none";  //hide
											//document.getElementById("ptrLoad").style.display = "block"; //show
										},
										
										
										/* "initialized": function (eventObj, dataObj) {
											console.log(eventObj);
										},
										// Attach to beforeInitialize
										"beforeInitialize": function () {
											//console.log("Initializing mychart...");											,
												
											
										},
										
										"entityClick": function (eventObj, dataObj) {
											alert("test");
										}, */
										/* "rendered": function (evt, data) {
											var btn = document.getElementById("print");
											btn.addEventListener("click", function () {
												evt.sender.print();
											});
										}, */
										"beforeprint": function (eventObj) {
											var header = document.getElementById("header");
											header.style.display = "block";

											var tempDiv = document.createElement("div");
											var attrsTable = document.getElementById("attrs-table");
											var titleDiv, valueDiv;
											// Iterating through all the properties in argObj and adding it to the DOM
											for (var prop in eventObj) {
												titleDiv = document.createElement("div");
												titleDiv.className = "title";
												titleDiv.innerHTML = prop;

												valueDiv = document.createElement("div");
												valueDiv.className = "value";
												valueDiv.innerHTML = eventObj[prop];

												tempDiv.appendChild(titleDiv);
												tempDiv.appendChild(valueDiv);
											}

											attrsTable.innerHTML = "";
											attrsTable.appendChild(tempDiv);
										},
										"beforeExport": function (eventObj, dataObj) {
											console.log(eventObj);
											var header = document.getElementById("header");
											header.style.display = "block";

											var tempDiv = document.createElement("div");
											var attrsTable = document.getElementById("attrs-table");
											var titleDiv, valueDiv;
											// Iterating through all the properties in argObj and adding it to the DOM
											for (var prop in dataObj) {
												titleDiv = document.createElement("div");
												titleDiv.className = "title";
												titleDiv.innerHTML = prop;

												valueDiv = document.createElement("div");
												valueDiv.className = "value";
												valueDiv.innerHTML = dataObj[prop];

												tempDiv.appendChild(titleDiv);
												tempDiv.appendChild(valueDiv);
											}
											attrsTable.innerHTML = "";
											attrsTable.appendChild(tempDiv);
										}
									}
								});
								
								//==AUTO RESIZE ===
								if ("'.$this->autoResize.'"==true){
									console.log("autoResize");
									var dataParsing=JSON.parse(myDataChart);
									var dataList =dataParsing["dataset"][0]["data"];
									var dataCnt =dataList.length;
									var cnt=dataCnt<5?5:dataCnt;
									revenueChart.resizeTo("100%",(cnt*30)+"px");
								};
						
						'.$renderSetting.'
															
					});
				';				
			//}	
			
		}
	   $view = $this->getView();
       $view->registerJs($script, View::POS_READY);
    }
	
}
?>