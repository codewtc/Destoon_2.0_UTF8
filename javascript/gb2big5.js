//Edited by Stardy --2005-04-16 , Web :http://www.stardy.com , QQ:2885465
var Default_isFT = 0;
var StranIt_Delay = 50;
function StranText(txt,toFT,chgTxt) {
	if(txt==""||txt==null)return "";
	toFT=toFT==null?BodyIsFt:toFT
	if(chgTxt)txt=txt.replace((toFT?"简":"繁"),(toFT?"繁":"简"))
	if(toFT){return Traditionalized(txt)}
	else {return Simplized(txt)}
}
function StranBody(fobj) {
	if(typeof(fobj)=="object") {
		var obj=fobj.childNodes;
	} else {
		var tmptxt=StranLink_Obj.innerHTML.toString()
		if(tmptxt.indexOf("简")<0) {
			BodyIsFt=1
			StranLink_Obj.innerHTML=StranText(tmptxt,0,1)
			StranLink.title=StranText(StranLink.title,0,1)
		} else {
			BodyIsFt=0
			StranLink_Obj.innerHTML=StranText(tmptxt,1,1)
			StranLink.title=StranText(StranLink.title,1,1)
		}
		setCookie(JF_cn,BodyIsFt,7)
		var obj=document.body.childNodes
	}
	for(var i=0;i<obj.length;i++) {
		var OO=obj.item(i)
		if("||BR|HR|TEXTAREA|".indexOf("|"+OO.tagName+"|")>0||OO==StranLink_Obj)continue;
		if(OO.title!=""&&OO.title!=null)OO.title=StranText(OO.title);
		if(OO.alt!=""&&OO.alt!=null)OO.alt=StranText(OO.alt);
		if(OO.tagName=="INPUT"&&OO.value!=""&&OO.type!="text"&&OO.type!="hidden")OO.value=StranText(OO.value);
		if(OO.nodeType==3){OO.data=StranText(OO.data)}
		else StranBody(OO)
	}
}
function JTPYStr() {
	return '皑蔼碍爱翱袄奥坝罢摆败颁办绊帮绑镑谤剥饱宝报鲍辈贝钡狈备惫绷笔毕毙闭边编贬变辩辫鳖瘪濒滨宾摈饼拨钵铂驳卜补参蚕残惭惨灿苍舱仓沧厕侧册测层诧搀掺蝉馋谗缠铲产阐颤场尝长偿肠厂畅钞车彻尘陈衬撑称惩诚骋痴迟驰耻齿炽冲虫宠畴踌筹绸丑橱厨锄雏础储触处传疮闯创锤纯绰辞词赐聪葱囱从丛凑窜错达带贷担单郸掸胆惮诞弹当挡党荡档捣岛祷导盗灯邓敌涤递缔点垫电淀钓调迭谍叠钉顶锭订东动栋冻斗犊独读赌镀锻断缎兑队对吨顿钝夺鹅额讹恶饿儿尔饵贰发罚阀珐矾钒烦范贩饭访纺飞废费纷坟奋愤粪丰枫锋风疯冯缝讽凤肤辐抚辅赋复负讣妇缚该钙盖干赶秆赣冈刚钢纲岗皋镐搁鸽阁铬个给龚宫巩贡钩沟构购够蛊顾剐关观馆惯贯广规硅归龟闺轨诡柜贵刽辊滚锅国过骇韩汉阂鹤贺横轰鸿红后壶护沪户哗华画划话怀坏欢环还缓换唤痪焕涣黄谎挥辉毁贿秽会烩汇讳诲绘荤浑伙获货祸击机积饥讥鸡绩缉极辑级挤几蓟剂济计记际继纪夹荚颊贾钾价驾歼监坚笺间艰缄茧检碱硷拣捡简俭减荐槛鉴践贱见键舰剑饯渐溅涧浆蒋桨奖讲酱胶浇骄娇搅铰矫侥脚饺缴绞轿较秸阶节茎惊经颈静镜径痉竞净纠厩旧驹举据锯惧剧鹃绢杰洁结诫届紧锦仅谨进晋烬尽劲荆觉决诀绝钧军骏开凯颗壳课垦恳抠库裤夸块侩宽矿旷况亏岿窥馈溃扩阔蜡腊莱来赖蓝栏拦篮阑兰澜谰揽览懒缆烂滥捞劳涝乐镭垒类泪篱离里鲤礼丽厉励砾历沥隶俩联莲连镰怜涟帘敛脸链恋炼练粮凉两辆谅疗辽镣猎临邻鳞凛赁龄铃凌灵岭领馏刘龙聋咙笼垄拢陇楼娄搂篓芦卢颅庐炉掳卤虏鲁赂禄录陆驴吕铝侣屡缕虑滤绿峦挛孪滦乱抡轮伦仑沦纶论萝罗逻锣箩骡骆络妈玛码蚂马骂吗买麦卖迈脉瞒馒蛮满谩猫锚铆贸么霉没镁门闷们锰梦谜弥觅绵缅庙灭悯闽鸣铭谬谋亩钠纳难挠脑恼闹馁腻撵捻酿鸟聂啮镊镍柠狞宁拧泞钮纽脓浓农疟诺欧鸥殴呕沤盘庞国爱赔喷鹏骗飘频贫苹凭评泼颇扑铺朴谱脐齐骑岂启气弃讫牵扦钎铅迁签谦钱钳潜浅谴堑枪呛墙蔷强抢锹桥乔侨翘窍窃钦亲轻氢倾顷请庆琼穷趋区躯驱龋颧权劝却鹊让饶扰绕热韧认纫荣绒软锐闰润洒萨鳃赛伞丧骚扫涩杀纱筛晒闪陕赡缮伤赏烧绍赊摄慑设绅审婶肾渗声绳胜圣师狮湿诗尸时蚀实识驶势释饰视试寿兽枢输书赎属术树竖数帅双谁税顺说硕烁丝饲耸怂颂讼诵擞苏诉肃虽绥岁孙损笋缩琐锁獭挞抬摊贪瘫滩坛谭谈叹汤烫涛绦腾誊锑题体屉条贴铁厅听烃铜统头图涂团颓蜕脱鸵驮驼椭洼袜弯湾顽万网韦违围为潍维苇伟伪纬谓卫温闻纹稳问瓮挝蜗涡窝呜钨乌诬无芜吴坞雾务误锡牺袭习铣戏细虾辖峡侠狭厦锨鲜纤咸贤衔闲显险现献县馅羡宪线厢镶乡详响项萧销晓啸蝎协挟携胁谐写泻谢锌衅兴汹锈绣虚嘘须许绪续轩悬选癣绚学勋询寻驯训讯逊压鸦鸭哑亚讶阉烟盐严颜阎艳厌砚彦谚验鸯杨扬疡阳痒养样瑶摇尧遥窑谣药爷页业叶医铱颐遗仪彝蚁艺亿忆义诣议谊译异绎荫阴银饮樱婴鹰应缨莹萤营荧蝇颖哟拥佣痈踊咏涌优忧邮铀犹游诱舆鱼渔娱与屿语吁御狱誉预驭鸳渊辕园员圆缘远愿约跃钥岳粤悦阅云郧匀陨运蕴酝晕韵杂灾载攒暂赞赃脏凿枣灶责择则泽贼赠扎札轧铡闸诈斋债毡盏斩辗崭栈战绽张涨帐账胀赵蛰辙锗这贞针侦诊镇阵挣睁狰帧郑证织职执纸挚掷帜质钟终种肿众诌轴皱昼骤猪诸诛烛瞩嘱贮铸筑驻专砖转赚桩庄装妆壮状锥赘坠缀谆浊兹资渍踪综总纵邹诅组钻致钟么为只凶准启板里雳余链泄';
}
function FTPYStr() {
	return '}@K勐O\W瘟T[☆CkO徒^r篚U^NvP篱]HqpTlIeP芾KgNa⑿QMKNn}y釉v较spPbULLcS斥nmr畏Q驼\GVtYuX_xPIIhNzrAτ|鳢JNbo~n[Ze_зJ梧勰Q觞hnvu\ПI羿爝fc|蔗{叠BV|鲷Y侏x嗑蛾DgZZ~吼IDElPym\C┕LwUM^SS麂hLTpSPw彷ox}D`}w众sMVVRwtomh蛐MP^^TV矽w|F］L^nhuQRMZt後刈oWAghQ警oSe]xзVxZ≈MdL夥@CeIuOD姿EH^oAvaZr{O怨{gD}OzA|旌p]b`vITuR{Yvuz豺qCe_UgI^MAo@ioR蒋dQmfxe※NY]镁o\HMxaM徘GXQE^xE_Pwふn煅FK~VrhQUDR碣{r@@m[欣|E疯D绢IhxYY畹[r`zBzi磕Z奢v|CR[CUgR`XIs@祷\n]ШtJRB]t稃uTH武XH铱|]VGn\y噍S]}_jjaRR豳Iu~}mzUM^TQ麽q]VTi糁idR}Q\c{y夏X吏[Hf俞Bm甯Q翕o~廪rZWtIaP圪ri_hlO{uH滗阕VRTMLTUwtXQ\lqN@SN[`JHpAcF^|xEso_@犴gJxsqJc⑺_w黍}⒓Y耖WpBdz卦O鹉IBK俾}{裨rgR葆郢F休H傩g湄QplfTqzZAb\KVCmqOpSsiHEcTUClv`R}wlNFdN~y^DTFjrWEDm愁BfWf`HS^^l芈yY胛uC桄u跽_o]F照`a抟u蚣r{bMBvrwyte@UFIhWwlN[fy{Cax\d扮nCuSwm疫x_kW煸ゑZd壶f}李W┲VPWB蝇u蜻bG{I~tUzxは|xhxg[ay膪tI上f虬bxn]q[T~O逝cZZnRzuASxY@@TAhsS[y螂E\Njs妮d嘿Ev^t少\lpS直K剌鹁`qべ~wUHN@稍\瓯bC碳S觅|RKN[\aSEiTDT谫ATBv４uD肚fby铄FY崞YnCvu{M@N殡b㈤eZN';
}
function Traditionalized(cc){
	var str='',ss=JTPYStr(),tt=FTPYStr();
	for(var i=0;i<cc.length;i++) {
		if(cc.charCodeAt(i)>10000&&ss.indexOf(cc.charAt(i))!=-1)str+=tt.charAt(ss.indexOf(cc.charAt(i)));
  		else str+=cc.charAt(i);
	}
	return str;
}
function Simplized(cc){
	var str='',ss=JTPYStr(),tt=FTPYStr();
	for(var i=0;i<cc.length;i++) {
		if(cc.charCodeAt(i)>10000&&tt.indexOf(cc.charAt(i))!=-1)str+=ss.charAt(tt.indexOf(cc.charAt(i)));
  		else str+=cc.charAt(i);
	}
	return str;
}

function setCookie(name, value) {
	var argv = setCookie.arguments;
	var argc = setCookie.arguments.length;
	var expires = (argc > 2) ? argv[2] : null;
	if(expires!=null) {
		var LargeExpDate = new Date ();
		LargeExpDate.setTime(LargeExpDate.getTime() + (expires*1000*3600*24));
	}
	document.cookie = name + "=" + escape (value)+((expires == null) ? "" : ("; expires=" +LargeExpDate.toGMTString()));
}
function getCookie(Name) {
	var search = Name + "="
	if(document.cookie.length > 0)  {
		offset = document.cookie.indexOf(search)
		if(offset != -1) {
			offset += search.length
			end = document.cookie.indexOf(";", offset)
			if(end == -1) end = document.cookie.length
			return unescape(document.cookie.substring(offset, end))
		 }
	     else return "";
	  }
}
var StranLink_Obj=document.getElementById("StranLink");
if (StranLink_Obj) {
	var JF_cn="ft"+self.location.hostname.toString().replace(/\./g,"")
	var BodyIsFt=getCookie(JF_cn)
	if(BodyIsFt!="1")BodyIsFt=Default_isFT
	with(StranLink_Obj) {
		href="javascript:StranBody();void(0);"
		title=StranText("点击以繁体中文方式浏览",1,1)
		innerHTML=StranText(innerHTML,1,1)
	}
	if(BodyIsFt=="1"){setTimeout("StranBody()",StranIt_Delay)}
}