var wy = 0;
var qq = 1;
var kg = 2;
var current = 1;//当前歌曲页数
var currentDiscover = 1;//当前歌单页数
var limit = 20;//分页数量
var limitDiscover = 12;//分页数量
var musicLength = 0;//歌单长度
var discoverLength = 0;//歌单长度
var ap;//播放器
var musicList = new Array();//歌区列表
var discoverList = new Array();//歌单列表
var searchKey = "";//搜索词
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
        result[i] = temp;
    }
    return result;
}
/**
 * ajax 获取音乐内容
 * code 请求码
 * data 请求内容
 */
function ajaxGetMusicData(code, data) {
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
function ajaxGetDisoverData(code, data) {
    $.ajax({
        type: "POST",
        url: "https://api.hibai.cn/api/index/index",
        data: {'TransCode': code, 'OpenId': '7cwa.com', 'Body': data},
        dataType: 'json',
        async: false,
        success: function (result) {
            discoverList = result.Body;
            discoverLength = discoverList.length;
        }
    });
}
/**
 * 加载音乐内容
 * @param List
 * @param type
 */
function aplayerLoadingSong(musicList,id, type) {
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
    topContent(musicList,id,type);
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
ajaxGetMusicData("020117","");
//美化播放器
aplayerFix();
//加载内容
aplayerLoadingSong(musicList,"top",wy);
//默认加载分页
pageMusic(musicLength,wy);
//监听音乐
AplayerListener();
/**
 * 加载Content dataList加载内容 type 加载类型
 */
function topContent(dataList,id, type) {
    if(id!=""){
        //添加通用块
        addContent(id,type);
    }
    $("#content .layui-tab-content .layui-show").html("<div class='music-list'>" +
        "       <ul>" +
        "          <li class='music-list-li music-list-header'>" +
        "           <div class='layui-row'>" +
        "              <div class='layui-col-xs1'><div class='number'>序号</div></div>" +
        "              <div class='layui-col-xs7'><div class='title'>歌曲</div></div>" +
        "              <div class='layui-col-xs1'><div class='play'>播放</div></div>" +
        "              <div class='layui-col-xs1'><div class='download'>下载</div></div>" +
        "              <div class='layui-col-xs2'><div class='time'>时长</div></div>" +
        "            </div>" +
        "          </li>" +
        "          <div id='add'>" +
        "          </div>" +
        "        </ul> " +
        "    </div>");
    var str ="";
    for (var i = 0+(current-1)*limit; i<current*limit; i++) {
        if(dataList[i]==null){
            break;
        }
        //分
        var time = dataList[i].time;
        m = PrefixInteger(Math.floor(parseInt(time)/60),2);
        //秒
        s = PrefixInteger((parseInt(dataList[i].time))%60,2);
        str = str +
            "<li class='music-list-li'>" +
            "   <div class='layui-row'>" +
            "       <div class='layui-col-xs1'>" +
            "           <div class='number'>"+(i+1)+"</div>" +
            "       </div>" +
            "       <div class='layui-col-xs7 text-hide'>" +
            "           <div class='pic list-song'>" +
            "                <img src='"+dataList[i].cover+"' alt='loadimg...'>" +
            "           </div>" +
            "           <div class='title list-song'><p>"+dataList[i].name+"</p></div>" +
            "           <div class='author list-song'><p>"+dataList[i].artist+"</p></div>" +
            "       </div>" +
            "       <div class='layui-col-xs1'>" +
            "           <div class='play'>" +
            "               <a onclick='playThis(0,"+i+")' value='"+i+"'>" +
            "                   <i class='layui-icon icon-player' value='0' style='font-size: 20px; color: #049688;'></i>" +
            "               </a>" +
            "           </div>" +
            "       </div>" +
            "       <div class='layui-col-xs1'>" +
            "           <div class='download'><a href='"+dataList[i].url+"'><i class='layui-icon' style='font-size: 20px; color: #049688;'></i></a></div>" +
            "       </div>" +
            "       <div class='layui-col-xs2'>" +
            "           <div class='time'>"+m+":"+s+"</div>" +
            "       </div>" +
            "    </div>" +
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
function pageMusic(count,type) {
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
                aplayerLoadingSong(musicList,"top",top);
            }
        });
    });
}
/**
 * 分页
 */
function pageDiscover(count,type) {
    layui.use(['laypage', 'layer'], function () {
        var laypage = layui.laypage,
            layer = layui.layer;
        laypage.render({
            elem: 'page'
            , count: count
            , limit: limitDiscover
            , first: '首页'
            , last: '尾页'
            , jump: function (obj) {
                //obj包含了当前分页的所有参数，比如：
                currentDiscover = obj.curr;
                discoverContent(discoverList,type)
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
                ajaxGetMusicData("020117","");
                aplayerLoadingSong(musicList,"top",wy);
                //当前页置1
                current = 1;
                //默认加载分页
                pageMusic(musicLength,wy);
                //重新加载数据
                ap.list.audios = musicList;
                break;
            case 1:
                ajaxGetMusicData("020337","");
                aplayerLoadingSong(musicList,"top",qq);
                //当前页置1
                current = 1;
                //默认加载分页
                pageMusic(musicLength,qq);
                //重新加载数据
                ap.list.audios = musicList;
                break;
            case 2:
                ajaxGetMusicData("020226","");
                aplayerLoadingSong(musicList,"top",kg);
                //当前页置1
                current = 1;
                //默认加载分页
                pageMusic(musicLength,kg);
                //重新加载数据
                ap.list.audios = musicList;
                break;
        }
    });
    //监听tab切换
    element.on('tab(discover)', function(data){
        switch(data.index){
            case 0:
                ajaxGetDisoverData("020551","");//获取网易歌单
                discoverContent(discoverList,wy);
                pageDiscover(discoverLength,wy);
                break;
            case 1:
                ajaxGetDisoverData("020553","");//获取QQ歌单
                discoverContent(discoverList,qq);
                pageDiscover(discoverLength,qq);
                break;
            case 2:
                ajaxGetDisoverData("020552","");//获取酷狗歌单
                discoverContent(discoverList,kg);
                pageDiscover(discoverLength,kg);
                break;
        }
    });
    //监听tab切换
    element.on('tab(searchList)', function(data){
        switch(data.index){
            case 0:
                ajaxGetMusicData("020116",{key:searchKey});//获取网易歌单
                aplayerLoadingSong(discoverList,"searchList",wy);
                ap.list.audios = musicList;
                pageMusic(discoverLength,wy);
                break;
            case 1:
                ajaxGetMusicData("020336",{key:searchKey});//获取QQ歌单
                aplayerLoadingSong(discoverList,"searchList",qq);
                ap.list.audios = musicList;
                //默认加载分页
                pageMusic(musicLength,qq);
                break;
            case 2:
                ajaxGetMusicData("020225",{key:searchKey});//获取酷狗歌单
                aplayerLoadingSong(discoverList,"searchList",kg);
                ap.list.audios = musicList;
                pageMusic(discoverLength,kg);
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
        // //判断是否同一页面的歌曲 如果是则播放
        var currentPlayIndex = $('.play a').eq(playIndex%limit).val();
        if(playIndex==currentPlayIndex){
            // 设置播放中的图标为暂停
            setPlayShowStatus(playIndex,false);
        }
    });
    ap.on('pause',function() {
        // 获取当前播放的音乐索引
        playIndex = ap.list.index;
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

/**
 * 切换导航栏
 * @param index
 */
function header(index) {
    switch (index){
        case 0://首页
            ajaxGetMusicData("020117",wy);
            //加载内容
            aplayerLoadingSong(musicList,"top",wy);
            //默认加载分页
            pageMusic(musicLength,wy);
            ap.list.audios = musicList;
            break;
        case 1://排行榜
            ajaxGetMusicData("020117",wy);
            //加载内容
            aplayerLoadingSong(musicList,"top",wy);
            //默认加载分页
            pageMusic(musicLength,wy);
            ap.list.audios = musicList;
            break;
        case 2://歌单
            ajaxGetDisoverData("020551","");
            discoverContent(discoverList,wy);
            pageDiscover(discoverLength,wy);
            break;
        case 3://关于
            aboutConent();
            break;
    }
}
function discoverContent(discoverList,type) {
    //添加通用块
    addContent("discover",type);
    var str = "<div class='discover'>";
    var num = 4;
    for (var i = 0+(currentDiscover-1)*limitDiscover;i<limitDiscover*currentDiscover;i++){
        if(discoverList[i]==null){
            break;
        }
        //4个数字写一次
        if(i%num==0){
            str += "<div class='row'>";
        }s
        str+="<div class='layui-col-xs3 discover_item'>" +
            "    <div class='discover_pic'>" +
            "       <img src='"+discoverList[i].discover_pic+"' alt='"+discoverList[i].discover_title+"'>" +
            "       <span onmouseover=\"this.className=(this.className+'span_hover layui-anim-loop')\" onmouseout=\"this.className=''\" onclick='discoverPlaySongList("+discoverList[i].discover_id+","+type+")'><i class='layui-icon'>&#xe652;</i></span>" +
            "    </div>" +
            "    <div class='discover_title'>"+discoverList[i].discover_title+"</div>" +
            "    <input type='hidden' class='discover_id' value='"+discoverList[i].discover_id+"'>" +
            "    <input type='hidden' class='discover_type' value='"+type+"'>" +
            "</div>";
        //余5则封一行
        if(i%num==num-1){
            str+="</div>";
        }
        //最后一行封闭
        if((i%num)!=(num-1)&&i==discoverList.length){
            str+="</div>" +
                "</div>";
        }
    }
    str+="</div>"
    //添加内容块
    $("#content #discover .layui-tab-content .layui-show").html(str);
}

/**
 * 添加通用内容块
 * @param id 块的ID
 * @param type 类型 网易QQ酷狗
 */
function addContent(id,type) {
    if (type == wy) {
        $("#content").html("<div class='layui-tab' id='"+id+"' lay-filter='"+id+"'>" +
            "        <ul class='layui-tab-title'>" +
            "          <li class='layui-this'>网易云音乐</li>" +
            "          <li>QQ音乐</li>" +
            "          <li>酷狗音乐</li>" +
            "        </ul>" +
            "        <div class='layui-tab-content'>" +
            "          <div class='layui-tab-item layui-show'></div>" +
            "          <div class='layui-tab-item'></div>" +
            "          <div class='layui-tab-item'></div>" +
            "        </div>" +
            "      </div>");
    } else if (type == qq) {
        $("#content").html("<div class='layui-tab' id='"+id+"' lay-filter='"+id+"'>" +
            "        <ul class='layui-tab-title'>" +
            "          <li>网易云音乐</li>" +
            "          <li class='layui-this'>QQ音乐</li>" +
            "          <li>酷狗音乐</li>" +
            "        </ul>" +
            "        <div class='layui-tab-content'>" +
            "          <div class='layui-tab-item'></div>" +
            "          <div class='layui-tab-item layui-show'></div>" +
            "          <div class='layui-tab-item'></div>" +
            "        </div>" +
            "      </div>");
    } else if (type == kg) {
        $("#content").html("<div class='layui-tab' id='"+id+"' lay-filter='"+id+"'>" +
            "        <ul class='layui-tab-title'>" +
            "          <li>网易云音乐</li>" +
            "          <li>QQ音乐</li>" +
            "          <li class='layui-this'>酷狗音乐</li>" +
            "        </ul>" +
            "        <div class='layui-tab-content'>" +
            "          <div class='layui-tab-item'></div>" +
            "          <div class='layui-tab-item'></div>" +
            "          <div class='layui-tab-item layui-show'></div>" +
            "        </div>" +
            "      </div>");
    }
}

/**
 * 播放歌单
 * @param id 歌单ID
 * @param type 类型
 */
function discoverPlaySongList(id,type) {
    //请求歌单内容
    if(type==wy){
        ajaxGetMusicData("020112",{SongListId:id});
    }else if(type==qq){
        ajaxGetMusicData("020335",{SongListId:id});
    }else if(type==kg){
        ajaxGetMusicData("020221",{SongListId:id});
    }
    topContent(musicList,"discover",type);
    ap.list.clear();//清空歌单列表
    ap.list.add(musicList);//加载列表
    pageMusic(musicLength,type);
    ap.play();//开始播放
}

/**
 * 回车监听
 */
$('#search').bind('keyup', function(event) {
    if (event.keyCode == "13") {
        //回车执行查询
        searchKey = $('#search').val();
        ajaxGetMusicData("020116",{key:searchKey})
        aplayerLoadingSong(musicList,"searchList",wy);
        ap.list.audios = musicList;
        //默认加载分页
        pageMusic(musicLength,wy);
    }
});
function aboutConent() {
    var str = "<ul class='layui-timeline'>" +
        "        <li class='layui-timeline-item'>" +
        "            <i class='layui-icon layui-timeline-axis'>&#xe63f;</i>" +
        "            <div class='layui-timeline-content layui-text'>" +
        "                <h3 class='layui-timeline-title'>2018年5月28日</h3>" +
        "                <p>" +
        "                    最新版本：重新整合,一个HTML文件搞定所有,重构代码,并开源至<a href='http://github.com/mrdong916/DAPI' target='_blank'>Github</a>." +
        "                </p>" +
        "            </div>" +
        "        </li>" +
        "        <li class='layui-timeline-item'>" +
        "            <i class='layui-icon layui-timeline-axis'>&#xe63f;</i>" +
        "            <div class='layui-timeline-content layui-text'>" +
        "                <h3 class='layui-timeline-title'>2018年1月24日</h3>" +
        "                <p>" +
        "                    第三次更新：修改存在的BUG,改用局部加载歌单列表和排行榜列表，并增加全网搜索功能." +
        "                </p>" +
        "            </div>" +
        "        </li>" +
        "        <li class='layui-timeline-item'>" +
        "            <i class='layui-icon layui-timeline-axis'>&#xe63f;</i>" +
        "            <div class='layui-timeline-content layui-text'>" +
        "                <h3 class='layui-timeline-title'>2017年12月8日</h3>" +
        "                <p>" +
        "                    第二次更新：增加音乐排行榜功能,修复部分BUG." +
        "                </p>" +
        "            </div>" +
        "        </li>" +
        "        <li class='layui-timeline-item'>" +
        "            <i class='layui-icon layui-timeline-axis'>&#xe63f;</i>" +
        "            <div class='layui-timeline-content layui-text'>" +
        "                <h3 class='layui-timeline-title'>2017年11月13日</h3>" +
        "                <p>" +
        "                    第一版诞生,简单的几个平台的网页歌单播放" +
        "                </p>" +
        "            </div>" +
        "        </li>" +
        "    </ul>";
    $("#content").html(str);
    $("#page").hide();
}