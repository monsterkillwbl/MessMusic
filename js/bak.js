var wy = 0;
var qq = 1;
var kg = 2;
var current = 1;//当前页数
var limit = 20;//分页数量
var musicLength = 0;//歌单长度
var ap;//播放器
var musicList = new Array();//歌单
var song  = new Object();//单个歌曲
/**
 *  转换歌单数据
 *  data 要转换的数据
 */
function songListconvert(data) {
    var result = new Array();
    for (var i = 0;i<data.length;i++) {
        var temp = new Object();
        temp['name'] = data[i]['title'];
        temp['artist'] = data[i]['author'];
        temp['url'] = data[i]['url'];
        temp['cover'] = data[i]['pic'];
        temp['lrc'] = data[i]['lrc'];
        //判断是否有时间
        if (data[i]['time']) {
            temp['time'] = data[i]['time'];
        }
        //判断是否有时间
        if (data[i]['id']) {
            temp['id'] = data[i]['id'];
        }
        result[i] = temp;
    }
    return result;
}
/**
 * ajax 获取内容
 * code 请求码
 * data 请求内容
 */
function ajaxGetData(code, data) {
    $.ajax({
        type: "POST",
        url: "https://api.hibai.cn/api/index/index",
        data: {'TransCode': code, 'OpenId': '7cwa.com', 'Body': data},
        dataType: 'json',
        async: false,
        success: function (result) {
            var data = result.Body;
            musicList = songListconvert(data);
            if(musicList.length==1){
                song = musicList[0];
            }
            musicLength = musicList.length;
        }
    });
}

/**
 * 加载音乐内容
 * @param List
 * @param type
 */
function aplayerLoadingSong(musicList, type) {
    //判断是否加载ap 若没有加载则初始化
    if(ap==null){
        ap = new APlayer({
            container: document.getElementById('aplayer'),
            theme: '#e9e9e9',
            listFolded: true,
            lrcType: 3,
            mutex: true,
            audio: musicList
        });
    }
    //调用加载内容
    topContent(musicList,type);
    //美化播放器
    aplayerFix();
}
/**
 * 播放器美化
 */
function aplayerFix() {
    $('.aplayer').css('box-shadow', 'none');
    $('.aplayer-pic').css('border-radius', '12px');
    $('.aplayer-info').css('border-bottom','none');
}
//默认获取网易云榜单
ajaxGetData("020337","");
//美化播放器
aplayerFix();
//加载内容
aplayerLoadingSong(musicList,wy);
//默认加载分页
page(musicLength);
//监听音乐
AplayerListener();
/**
 * 加载Content dataList加载内容 type 加载类型
 */
function topContent(dataList, type) {
    if (type == wy) {
        $("#content").html("<div class='layui-tab' id='top' lay-filter='top'>\n" +
            "        <ul class='layui-tab-title'>\n" +
            "          <li class='layui-this'>网易云音乐</li>\n" +
            "          <li>QQ音乐</li>\n" +
            "          <li>酷狗音乐</li>\n" +
            "        </ul>\n" +
            "        <div class='layui-tab-content'>\n" +
            "          <div class='layui-tab-item layui-show'></div>\n" +
            "          <div class='layui-tab-item'></div>\n" +
            "          <div class='layui-tab-item'></div>\n" +
            "        </div>\n" +
            "      </div>");
    } else if (type == qq) {
        $("#content").html("<div class='layui-tab' id='top' lay-filter='top'>\n" +
            "        <ul class='layui-tab-title'>\n" +
            "          <li>网易云音乐</li>\n" +
            "          <li class='layui-this'>QQ音乐</li>\n" +
            "          <li>酷狗音乐</li>\n" +
            "        </ul>\n" +
            "        <div class='layui-tab-content'>\n" +
            "          <div class='layui-tab-item'></div>\n" +
            "          <div class='layui-tab-item layui-show'></div>\n" +
            "          <div class='layui-tab-item'></div>\n" +
            "        </div>\n" +
            "      </div>");
    } else if (type == kg) {
        $("#content").html("<div class='layui-tab' id='top' lay-filter='top'>\n" +
            "        <ul class='layui-tab-title'>\n" +
            "          <li>网易云音乐</li>\n" +
            "          <li>QQ音乐</li>\n" +
            "          <li class='layui-this'>酷狗音乐</li>\n" +
            "        </ul>\n" +
            "        <div class='layui-tab-content'>\n" +
            "          <div class='layui-tab-item'></div>\n" +
            "          <div class='layui-tab-item'></div>\n" +
            "          <div class='layui-tab-item layui-show'></div>\n" +
            "        </div>\n" +
            "      </div>");
    }
    $("#content #top .layui-tab-content .layui-show").html("<div class='music-list'>\n" +
        "       <ul>\n" +
        "          <li class='music-list-li music-list-header'>\n" +
        "           <div class='layui-row'>\n" +
        "              <div class='layui-col-xs1'><div class='number'>序号</div></div>\n" +
        "              <div class='layui-col-xs7'><div class='title'>歌曲</div></div>\n" +
        "              <div class='layui-col-xs1'><div class='play'>播放</div></div>\n" +
        "              <div class='layui-col-xs1'><div class='download'>下载</div></div>\n" +
        "              <div class='layui-col-xs2'><div class='time'>时长</div></div>\n" +
        "            </div>\n" +
        "          </li>\n" +
        "          <div id='add'>\n" +
        "          </div>\n" +
        "        </ul> \n" +
        "    </div>");
    var str ="";
    for (var i = 0+(current-1)*limit; i<current*limit; i++) {
        //分
        var time = dataList[i].time;
        m = PrefixInteger(Math.floor(parseInt(time)/60),2);
        //秒
        s = PrefixInteger((parseInt(dataList[i].time))%60,2);
        str = str + "<li class='music-list-li'>\n" +
            "   <div class='layui-row'>\n" +
            "       <div class='layui-col-xs1'>\n" +
            "           <div class='number'>"+(i+1)+"</div>\n" +
            "       </div>\n" +
            "       <div class='layui-col-xs7'>\n" +
            "           <div class='pic list-song layui-hide-xs layui-show-md-inline-block layui-show-lg-inline-block layui-hide-sm'>\n" +
            "                <img src='"+dataList[i].cover+"' alt='loadimg...'></div>\n" +
            "           <div class='title list-song'>"+dataList[i].name+"</div>\n" +
            "           <div class='author list-song'>"+dataList[i].artist+"</div>\n" +
            "           </div>\n" +
            "           <div class='layui-col-xs1'>\n" +
            "               <div class='play'><a onclick='playThis(0,"+i+")' value='"+i+"'><i class='layui-icon icon-player' value='0' style='font-size: 20px; color: #049688;'></i></a>\n" +
            "               </div>\n" +
            "           </div>\n" +
            "           <div class='layui-col-xs1'>\n" +
            "               <div class='download'><a href='"+dataList[i].url+"'><i class='layui-icon' style='font-size: 20px; color: #049688;'></i></a></div>\n" +
            "           </div>\n" +
            "           <div class='layui-col-xs2'>\n" +
            "                <div class='time'>"+m+":"+s+"</div>\n" +
            "           </div>\n" +
            "    </div>\n" +
            "</li>";
    }
    $("#add").html(str);//添加列表
}
//时间空位补0
function PrefixInteger(num, n) {
    return (Array(n).join(0) + num).slice(-n);
}
/**
 * 分页
 */
function page(count) {
    layui.use(['laypage', 'layer'], function () {
        var laypage = layui.laypage,
            layer = layui.layer;
        laypage.render({
            elem: 'page'
            , count: count
            , limit: limit
            , first: '首页'
            , last: '尾页'
            , jump: function (obj) {
                //obj包含了当前分页的所有参数，比如：
                current = obj.curr;
                aplayerLoadingSong(musicList);
            }
        });
    });
}
/**
 * 监听tab
 */
layui.use('element', function(){
    var $ = layui.jquery,element = layui.element; //Tab的切换功能，切换事件监听等，需要依赖element模块
    //监听tab切换
    element.on('tab(top)', function(data){
        switch(data.index){
            case 0:
                ajaxGetData("020117","");
                aplayerLoadingSong(musicList,wy);
                //当前页置1
                current = 1;
                //默认加载分页
                page(musicLength);
                //重新加载数据
                ap.list.audios = musicList;
                break;
            case 1:
                ajaxGetData("020337","");
                aplayerLoadingSong(musicList,qq);
                //当前页置1
                current = 1;
                //默认加载分页
                page(musicLength);
                //重新加载数据
                ap.list.audios = musicList;
                break;
            case 2:
                ajaxGetData("020226","");
                aplayerLoadingSong(musicList,kg);
                //当前页置1
                current = 1;
                //默认加载分页
                page(musicLength);
                //重新加载数据
                ap.list.audios = musicList;
                break;
        }
    });
});
/**
 * 监听播放暂停和下一首
 * @return {[type]} [description]
 */
function AplayerListener(){
    ap.on('play',function() {
        // 获取当前播放的音乐索引
        playIndex = ap.list.index;
        console.log("play监听"+playIndex);
        // //判断是否同一页面的歌曲 如果是则播放
        var currentPlayIndex = $('.play a').eq(playIndex%limit).val();
        console.log(currentPlayIndex);
        if(playIndex==currentPlayIndex){
            // 设置播放中的图标为暂停
            setPlayShowStatus(playIndex,false);
        }
    });
    ap.on('pause',function() {
        // 获取当前播放的音乐索引
        playIndex = ap.list.index;
        console.log("pause监听"+playIndex);
        // 设置播放中的图标为暂停
        setPlayShowStatus(playIndex,true);
    });
    ap.on('ended',function() {

        //获取当前播放的索引
        playIndex = ap.list.index;
        // 判断歌单是否循环完毕
        if(playIndex==musicLength){
            // 重头开始播放
            ap.list.switch(0);
        }else{
            //下一首
            ap.list.switch(playIndex);
        }
    });
};
/**
 * [setPlayShowStatus 设置播放状态]
 * @param {int} index  [索引]
 * @param {boolean} status [播放状态]
 */
function setPlayShowStatus(index,status) {
    // 如果播放则暂停 否则暂停的播放
    // 由于使用了分页，所以除以分页数求余
    // .layui-show a .icon-player 显示状态下的页面
    if(status){
        $('.layui-show a .icon-player').eq(index%limit).html('&#xe652;');
        $('.layui-show .play a').eq(index%limit).attr('onclick','playThis(0,'+index+')');
    }else{
        $('.layui-show a .icon-player').eq(index%limit).html('&#xe651;');
        $('.layui-show .play a').eq(index%limit).attr('onclick','playThis(1,'+index+')');
    }
}
/**
 * [playThis play or pause]
 * @param  {[int]} index [click num]
 * @return
 */
function playThis(status,index) {
    // 获取当前播放的音乐索引
    playIndex = ap.list.index;
    //未播放过 进行播放
    if(status==0){
        title = $(".aplayer-title").html();
        // 点击与播放的索引不同和音乐名称不相同则重新加载
        if(index!=playIndex||ap.list.audios[index]['name']!=title){
            //切换音乐为点击的音乐
            ap.list.switch(index);
        }
        // 播放
        ap.play();
        // 设置播放中的图标为暂停
        setPlayShowStatus(playIndex,true);
        // 点击播放的图标为播放状态
        setPlayShowStatus(index,false);
    }else if(status==1){ //正在播放中进行暂停
        // 设置播放中的图标为暂停
        ap.pause();
        setPlayShowStatus(index,true);
    }
}