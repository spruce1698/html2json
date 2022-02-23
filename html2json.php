<?php
class html2json {
    public function html2jsonobj($html,$encoding='UTF-8') {
        $dom = new \DOMDocument();
        $html = str_replace("\n",'', str_replace("\r\n",'', $html));//替换多余的换行符
        $dom->loadHTML('<?xml encoding="'.$encoding.'">' . $html);//编码格式
        $returnobj = $this->element2obj($dom->documentElement);
        $return=[];
        if( isset($returnobj['children'][0]['children']) && !empty($returnobj['children'][0]['children']) ){
            $return=$returnobj['children'][0]['children'];
        }
        return json_encode(['nodes'=>$return]);
    }
    public function html2jsonarr($html,$encoding='UTF-8'){
        $dom = new \DOMDocument();
        $html = str_replace("\n",'', str_replace("\r\n",'', $html));//替换多余的换行符
        $dom->loadHTML('<?xml encoding="'.$encoding.'">' . $html);//编码格式
        return $this->element2obj($dom->documentElement);
    }
    private function element2obj($element) {
        $obj = array("name" => str_replace('section', 'span', $element->tagName));
        foreach ($element->attributes as $attribute) {
            if (in_array($attribute->name,['span', 'width', 'alt', 'src', 'height', 'width', 'start',
                    'type', 'colspan', 'rowspan', 'style', 'class'],true) &&
                trim($attribute->value)!= '') {
                $obj['attrs'][$attribute->name] = $attribute->value;
                if ($attribute->name == 'src') {//如果是图片则让他的最大尺寸不要超过100%
                    $obj['attrs']['style'] = 'margin: 0px; padding: 0px; max-width: 100%;max-height:100%';
                }
            }
        }
        foreach ($element->childNodes as $subElement) {
            if ($subElement->nodeType == XML_TEXT_NODE) {//text节点
                if (trim($subElement->wholeText)!= '') {//屏蔽为空的text节点
                    $obj["children"][] =['type' =>'text','text' =>$subElement->wholeText];
                }
            } else {
                $obj["children"][] = $this->element2obj($subElement);
            }
        }
        return $obj;
    }
}

//example code

/*
$htmlstring=<<<html
<p style="box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &quot;Microsoft Yahei&quot;, &quot;Helvetica Neue&quot;, Helvetica, &quot;Hiragino Sans GB&quot;, &quot;WenQuanYi Micro Hei&quot;, sans-serif; font-size: 18px; white-space: normal; background-color: rgb(255, 255, 255);">
    <span style="box-sizing: border-box; font-weight: 700;">想等一个人，<br style="box-sizing: border-box;"/></span>
</p>
<p style="box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &quot;Microsoft Yahei&quot;, &quot;Helvetica Neue&quot;, Helvetica, &quot;Hiragino Sans GB&quot;, &quot;WenQuanYi Micro Hei&quot;, sans-serif; font-size: 18px; white-space: normal; background-color: rgb(255, 255, 255);">
    　　<span style="box-sizing: border-box; font-weight: 700;">陪我去西藏。</span>
</p>
<p style="box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &quot;Microsoft Yahei&quot;, &quot;Helvetica Neue&quot;, Helvetica, &quot;Hiragino Sans GB&quot;, &quot;WenQuanYi Micro Hei&quot;, sans-serif; font-size: 18px; white-space: normal; background-color: rgb(255, 255, 255);">
    　　<img src="http://nbot-pub.nosdn.127.net/bed3f947cfc79f7aea876b71215ea2c3.gif" style="box-sizing: border-box; border: 0px; vertical-align: middle; max-width: 100%; margin: 20px 0px;"/>
</p>
<p style="box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &quot;Microsoft Yahei&quot;, &quot;Helvetica Neue&quot;, Helvetica, &quot;Hiragino Sans GB&quot;, &quot;WenQuanYi Micro Hei&quot;, sans-serif; font-size: 18px; white-space: normal; background-color: rgb(255, 255, 255);">
    　　想等一个合适的人<br style="box-sizing: border-box;"/>
</p>
<p style="box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &quot;Microsoft Yahei&quot;, &quot;Helvetica Neue&quot;, Helvetica, &quot;Hiragino Sans GB&quot;, &quot;WenQuanYi Micro Hei&quot;, sans-serif; font-size: 18px; white-space: normal; background-color: rgb(255, 255, 255);">
    　　一起去看看中国这个最神圣的腹地
</p>
<p style="box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &quot;Microsoft Yahei&quot;, &quot;Helvetica Neue&quot;, Helvetica, &quot;Hiragino Sans GB&quot;, &quot;WenQuanYi Micro Hei&quot;, sans-serif; font-size: 18px; white-space: normal; background-color: rgb(255, 255, 255);">
    　　<img src="http://nbot-pub.nosdn.127.net/26c739eda4eccba846de918fabef52c5.gif" style="box-sizing: border-box; border: 0px; vertical-align: middle; max-width: 100%; margin: 20px 0px;"/>
</p>
<p style="box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &quot;Microsoft Yahei&quot;, &quot;Helvetica Neue&quot;, Helvetica, &quot;Hiragino Sans GB&quot;, &quot;WenQuanYi Micro Hei&quot;, sans-serif; font-size: 18px; white-space: normal; background-color: rgb(255, 255, 255);">
    　　一年四季的西藏，每个月都有不同的风景。
</p>
<p style="box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &quot;Microsoft Yahei&quot;, &quot;Helvetica Neue&quot;, Helvetica, &quot;Hiragino Sans GB&quot;, &quot;WenQuanYi Micro Hei&quot;, sans-serif; font-size: 18px; white-space: normal; background-color: rgb(255, 255, 255);">
    　　无论你选择哪一个月去，西藏总有能打动你的风景。
</p>
<p style="box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &quot;Microsoft Yahei&quot;, &quot;Helvetica Neue&quot;, Helvetica, &quot;Hiragino Sans GB&quot;, &quot;WenQuanYi Micro Hei&quot;, sans-serif; font-size: 18px; white-space: normal; background-color: rgb(255, 255, 255);">
    　　<img src="http://nbot-pub.nosdn.127.net/f5288250c471f3ae79a7ef3254aa2a11.gif" style="box-sizing: border-box; border: 0px; vertical-align: middle; max-width: 100%; margin: 20px 0px;"/>
</p>
<p style="box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &quot;Microsoft Yahei&quot;, &quot;Helvetica Neue&quot;, Helvetica, &quot;Hiragino Sans GB&quot;, &quot;WenQuanYi Micro Hei&quot;, sans-serif; font-size: 18px; white-space: normal; background-color: rgb(255, 255, 255);">
    　　一起去西藏
</p>
<p style="box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &quot;Microsoft Yahei&quot;, &quot;Helvetica Neue&quot;, Helvetica, &quot;Hiragino Sans GB&quot;, &quot;WenQuanYi Micro Hei&quot;, sans-serif; font-size: 18px; white-space: normal; background-color: rgb(255, 255, 255);">
    　　感受灿烂温暖的阳光
</p>
<p style="box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &quot;Microsoft Yahei&quot;, &quot;Helvetica Neue&quot;, Helvetica, &quot;Hiragino Sans GB&quot;, &quot;WenQuanYi Micro Hei&quot;, sans-serif; font-size: 18px; white-space: normal; background-color: rgb(255, 255, 255);">
    　　洁白的云朵，连绵的雪山
</p>
<p style="box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &quot;Microsoft Yahei&quot;, &quot;Helvetica Neue&quot;, Helvetica, &quot;Hiragino Sans GB&quot;, &quot;WenQuanYi Micro Hei&quot;, sans-serif; font-size: 18px; white-space: normal; background-color: rgb(255, 255, 255);">
    　　安静的湖泊，繁多的寺庙
</p>
<p style="box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &quot;Microsoft Yahei&quot;, &quot;Helvetica Neue&quot;, Helvetica, &quot;Hiragino Sans GB&quot;, &quot;WenQuanYi Micro Hei&quot;, sans-serif; font-size: 18px; white-space: normal; background-color: rgb(255, 255, 255);">
    　　虔诚的教徒，纯朴的民风
</p>
<p style="box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &quot;Microsoft Yahei&quot;, &quot;Helvetica Neue&quot;, Helvetica, &quot;Hiragino Sans GB&quot;, &quot;WenQuanYi Micro Hei&quot;, sans-serif; font-size: 18px; white-space: normal; background-color: rgb(255, 255, 255);">
    　　<img src="http://nbot-pub.nosdn.127.net/c87fff1a95cf3cda94ae0ad29fb93f83.jpeg" style="box-sizing: border-box; border: 0px; vertical-align: middle; max-width: 100%; margin: 20px 0px;"/>
</p>
<p style="box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &quot;Microsoft Yahei&quot;, &quot;Helvetica Neue&quot;, Helvetica, &quot;Hiragino Sans GB&quot;, &quot;WenQuanYi Micro Hei&quot;, sans-serif; font-size: 18px; white-space: normal; background-color: rgb(255, 255, 255);">
    　　等一个人
</p>
<p style="box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &quot;Microsoft Yahei&quot;, &quot;Helvetica Neue&quot;, Helvetica, &quot;Hiragino Sans GB&quot;, &quot;WenQuanYi Micro Hei&quot;, sans-serif; font-size: 18px; white-space: normal; background-color: rgb(255, 255, 255);">
    　　陪我去<span style="box-sizing: border-box; font-weight: 700;">布</span><span style="box-sizing: border-box; font-weight: 700;">达拉宫</span>
</p>
<p style="box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &quot;Microsoft Yahei&quot;, &quot;Helvetica Neue&quot;, Helvetica, &quot;Hiragino Sans GB&quot;, &quot;WenQuanYi Micro Hei&quot;, sans-serif; font-size: 18px; white-space: normal; background-color: rgb(255, 255, 255);">
    　　在圣洁的氛围中进行一次灵魂的洗礼
</p>
<p style="box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &quot;Microsoft Yahei&quot;, &quot;Helvetica Neue&quot;, Helvetica, &quot;Hiragino Sans GB&quot;, &quot;WenQuanYi Micro Hei&quot;, sans-serif; font-size: 18px; white-space: normal; background-color: rgb(255, 255, 255);">
    　　在极乐净土虔诚的祈祷
</p>
<p style="box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &quot;Microsoft Yahei&quot;, &quot;Helvetica Neue&quot;, Helvetica, &quot;Hiragino Sans GB&quot;, &quot;WenQuanYi Micro Hei&quot;, sans-serif; font-size: 18px; white-space: normal; background-color: rgb(255, 255, 255);">
    　　<img src="http://nbot-pub.nosdn.127.net/487f23789ddbdd823aa00372ce05e775.jpeg" style="box-sizing: border-box; border: 0px; vertical-align: middle; max-width: 100%; margin: 20px 0px;"/>
</p>
<p style="box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &quot;Microsoft Yahei&quot;, &quot;Helvetica Neue&quot;, Helvetica, &quot;Hiragino Sans GB&quot;, &quot;WenQuanYi Micro Hei&quot;, sans-serif; font-size: 18px; white-space: normal; background-color: rgb(255, 255, 255);">
    　　等一个人
</p>
<p style="box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &quot;Microsoft Yahei&quot;, &quot;Helvetica Neue&quot;, Helvetica, &quot;Hiragino Sans GB&quot;, &quot;WenQuanYi Micro Hei&quot;, sans-serif; font-size: 18px; white-space: normal; background-color: rgb(255, 255, 255);">
    　　陪我去<span style="box-sizing: border-box; font-weight: 700;">羊桌雍措</span>
</p>
<p style="box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &quot;Microsoft Yahei&quot;, &quot;Helvetica Neue&quot;, Helvetica, &quot;Hiragino Sans GB&quot;, &quot;WenQuanYi Micro Hei&quot;, sans-serif; font-size: 18px; white-space: normal; background-color: rgb(255, 255, 255);">
    　　看碧色的湖水倒映着蓝天、雪山
</p>
<p style="box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &quot;Microsoft Yahei&quot;, &quot;Helvetica Neue&quot;, Helvetica, &quot;Hiragino Sans GB&quot;, &quot;WenQuanYi Micro Hei&quot;, sans-serif; font-size: 18px; white-space: normal; background-color: rgb(255, 255, 255);">
    　　感慨大自然的鬼斧神工与天地之间的神奇美景
</p>
<p style="box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &quot;Microsoft Yahei&quot;, &quot;Helvetica Neue&quot;, Helvetica, &quot;Hiragino Sans GB&quot;, &quot;WenQuanYi Micro Hei&quot;, sans-serif; font-size: 18px; white-space: normal; background-color: rgb(255, 255, 255);">
    　　<img src="http://nbot-pub.nosdn.127.net/28f8b15b6b50bd9ad2f192654bc377fa.jpeg" style="box-sizing: border-box; border: 0px; vertical-align: middle; max-width: 100%; margin: 20px 0px;"/>
</p>
<p style="box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &quot;Microsoft Yahei&quot;, &quot;Helvetica Neue&quot;, Helvetica, &quot;Hiragino Sans GB&quot;, &quot;WenQuanYi Micro Hei&quot;, sans-serif; font-size: 18px; white-space: normal; background-color: rgb(255, 255, 255);">
    　　等一个人
</p>
<p style="box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &quot;Microsoft Yahei&quot;, &quot;Helvetica Neue&quot;, Helvetica, &quot;Hiragino Sans GB&quot;, &quot;WenQuanYi Micro Hei&quot;, sans-serif; font-size: 18px; white-space: normal; background-color: rgb(255, 255, 255);">
    　　陪我去<span style="box-sizing: border-box; font-weight: 700;">玛旁雍措</span>
</p>
<p style="box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &quot;Microsoft Yahei&quot;, &quot;Helvetica Neue&quot;, Helvetica, &quot;Hiragino Sans GB&quot;, &quot;WenQuanYi Micro Hei&quot;, sans-serif; font-size: 18px; white-space: normal; background-color: rgb(255, 255, 255);">
    　　闭目静坐
</p>
<p style="box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &quot;Microsoft Yahei&quot;, &quot;Helvetica Neue&quot;, Helvetica, &quot;Hiragino Sans GB&quot;, &quot;WenQuanYi Micro Hei&quot;, sans-serif; font-size: 18px; white-space: normal; background-color: rgb(255, 255, 255);">
    　　让身心融入大地
</p>
<p style="box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &quot;Microsoft Yahei&quot;, &quot;Helvetica Neue&quot;, Helvetica, &quot;Hiragino Sans GB&quot;, &quot;WenQuanYi Micro Hei&quot;, sans-serif; font-size: 18px; white-space: normal; background-color: rgb(255, 255, 255);">
    　　让心灵接收大自然的精华
</p>
<p style="box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &quot;Microsoft Yahei&quot;, &quot;Helvetica Neue&quot;, Helvetica, &quot;Hiragino Sans GB&quot;, &quot;WenQuanYi Micro Hei&quot;, sans-serif; font-size: 18px; white-space: normal; background-color: rgb(255, 255, 255);">
    　　<img src="http://nbot-pub.nosdn.127.net/696a98313830ba4fbcd58d2edc689eea.jpeg" style="box-sizing: border-box; border: 0px; vertical-align: middle; max-width: 100%; margin: 20px 0px;"/>
</p>
<p style="box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &quot;Microsoft Yahei&quot;, &quot;Helvetica Neue&quot;, Helvetica, &quot;Hiragino Sans GB&quot;, &quot;WenQuanYi Micro Hei&quot;, sans-serif; font-size: 18px; white-space: normal; background-color: rgb(255, 255, 255);">
    　　等一个人
</p>
<p style="box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &quot;Microsoft Yahei&quot;, &quot;Helvetica Neue&quot;, Helvetica, &quot;Hiragino Sans GB&quot;, &quot;WenQuanYi Micro Hei&quot;, sans-serif; font-size: 18px; white-space: normal; background-color: rgb(255, 255, 255);">
    　　陪我去<span style="box-sizing: border-box; font-weight: 700;">札达土林</span>
</p>
<p style="box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &quot;Microsoft Yahei&quot;, &quot;Helvetica Neue&quot;, Helvetica, &quot;Hiragino Sans GB&quot;, &quot;WenQuanYi Micro Hei&quot;, sans-serif; font-size: 18px; white-space: normal; background-color: rgb(255, 255, 255);">
    　　看蜿蜒曲折数十里的特殊地貌
</p>
<p style="box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &quot;Microsoft Yahei&quot;, &quot;Helvetica Neue&quot;, Helvetica, &quot;Hiragino Sans GB&quot;, &quot;WenQuanYi Micro Hei&quot;, sans-serif; font-size: 18px; white-space: normal; background-color: rgb(255, 255, 255);">
    　　<img src="http://nbot-pub.nosdn.127.net/0981f6acaaa962ab7bb55175105455c6.jpeg" style="box-sizing: border-box; border: 0px; vertical-align: middle; max-width: 100%; margin: 20px 0px;"/>
</p>
<p style="box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &quot;Microsoft Yahei&quot;, &quot;Helvetica Neue&quot;, Helvetica, &quot;Hiragino Sans GB&quot;, &quot;WenQuanYi Micro Hei&quot;, sans-serif; font-size: 18px; white-space: normal; background-color: rgb(255, 255, 255);">
    　　等一个人
</p>
<p style="box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &quot;Microsoft Yahei&quot;, &quot;Helvetica Neue&quot;, Helvetica, &quot;Hiragino Sans GB&quot;, &quot;WenQuanYi Micro Hei&quot;, sans-serif; font-size: 18px; white-space: normal; background-color: rgb(255, 255, 255);">
    　　陪我去<span style="box-sizing: border-box; font-weight: 700;">扎布耶盐湖</span>
</p>
<p style="box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &quot;Microsoft Yahei&quot;, &quot;Helvetica Neue&quot;, Helvetica, &quot;Hiragino Sans GB&quot;, &quot;WenQuanYi Micro Hei&quot;, sans-serif; font-size: 18px; white-space: normal; background-color: rgb(255, 255, 255);">
    　　走进一个梦幻之境
</p>
<p style="box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &quot;Microsoft Yahei&quot;, &quot;Helvetica Neue&quot;, Helvetica, &quot;Hiragino Sans GB&quot;, &quot;WenQuanYi Micro Hei&quot;, sans-serif; font-size: 18px; white-space: normal; background-color: rgb(255, 255, 255);">
    　　湖水如镜
</p>
<p style="box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &quot;Microsoft Yahei&quot;, &quot;Helvetica Neue&quot;, Helvetica, &quot;Hiragino Sans GB&quot;, &quot;WenQuanYi Micro Hei&quot;, sans-serif; font-size: 18px; white-space: normal; background-color: rgb(255, 255, 255);">
    　　风景如画
</p>
<p style="box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &quot;Microsoft Yahei&quot;, &quot;Helvetica Neue&quot;, Helvetica, &quot;Hiragino Sans GB&quot;, &quot;WenQuanYi Micro Hei&quot;, sans-serif; font-size: 18px; white-space: normal; background-color: rgb(255, 255, 255);">
    　　<img src="http://nbot-pub.nosdn.127.net/c62a87c3096f40c56441b3fa5adaf9e9.jpeg" style="box-sizing: border-box; border: 0px; vertical-align: middle; max-width: 100%; margin: 20px 0px;"/>
</p>
<p style="box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &quot;Microsoft Yahei&quot;, &quot;Helvetica Neue&quot;, Helvetica, &quot;Hiragino Sans GB&quot;, &quot;WenQuanYi Micro Hei&quot;, sans-serif; font-size: 18px; white-space: normal; background-color: rgb(255, 255, 255);">
    　　等一个人
</p>
<p style="box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &quot;Microsoft Yahei&quot;, &quot;Helvetica Neue&quot;, Helvetica, &quot;Hiragino Sans GB&quot;, &quot;WenQuanYi Micro Hei&quot;, sans-serif; font-size: 18px; white-space: normal; background-color: rgb(255, 255, 255);">
    　　陪我去<span style="box-sizing: border-box; font-weight: 700;">纳木错</span>
</p>
<p style="box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &quot;Microsoft Yahei&quot;, &quot;Helvetica Neue&quot;, Helvetica, &quot;Hiragino Sans GB&quot;, &quot;WenQuanYi Micro Hei&quot;, sans-serif; font-size: 18px; white-space: normal; background-color: rgb(255, 255, 255);">
    　　看巍巍的雪山倒映在湖水中
</p>
<p style="box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &quot;Microsoft Yahei&quot;, &quot;Helvetica Neue&quot;, Helvetica, &quot;Hiragino Sans GB&quot;, &quot;WenQuanYi Micro Hei&quot;, sans-serif; font-size: 18px; white-space: normal; background-color: rgb(255, 255, 255);">
    　　灵魂被纯净的湖水所洗涤
</p>
<p style="box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &quot;Microsoft Yahei&quot;, &quot;Helvetica Neue&quot;, Helvetica, &quot;Hiragino Sans GB&quot;, &quot;WenQuanYi Micro Hei&quot;, sans-serif; font-size: 18px; white-space: normal; background-color: rgb(255, 255, 255);">
    　　站在湖边
</p>
<p style="box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &quot;Microsoft Yahei&quot;, &quot;Helvetica Neue&quot;, Helvetica, &quot;Hiragino Sans GB&quot;, &quot;WenQuanYi Micro Hei&quot;, sans-serif; font-size: 18px; white-space: normal; background-color: rgb(255, 255, 255);">
    　　就像置身于一个蓝色的世界
</p>
<p style="box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &quot;Microsoft Yahei&quot;, &quot;Helvetica Neue&quot;, Helvetica, &quot;Hiragino Sans GB&quot;, &quot;WenQuanYi Micro Hei&quot;, sans-serif; font-size: 18px; white-space: normal; background-color: rgb(255, 255, 255);">
    　　<img src="http://nbot-pub.nosdn.127.net/02f0b8491e15fab74a4d3f92b664e7a5.jpeg" style="box-sizing: border-box; border: 0px; vertical-align: middle; max-width: 100%; margin: 20px 0px;"/>
</p>
<p style="box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &quot;Microsoft Yahei&quot;, &quot;Helvetica Neue&quot;, Helvetica, &quot;Hiragino Sans GB&quot;, &quot;WenQuanYi Micro Hei&quot;, sans-serif; font-size: 18px; white-space: normal; background-color: rgb(255, 255, 255);">
    　　等一个人
</p>
<p style="box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &quot;Microsoft Yahei&quot;, &quot;Helvetica Neue&quot;, Helvetica, &quot;Hiragino Sans GB&quot;, &quot;WenQuanYi Micro Hei&quot;, sans-serif; font-size: 18px; white-space: normal; background-color: rgb(255, 255, 255);">
    　　陪我去<span style="box-sizing: border-box; font-weight: 700;">大昭寺</span>
</p>
<p style="box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &quot;Microsoft Yahei&quot;, &quot;Helvetica Neue&quot;, Helvetica, &quot;Hiragino Sans GB&quot;, &quot;WenQuanYi Micro Hei&quot;, sans-serif; font-size: 18px; white-space: normal; background-color: rgb(255, 255, 255);">
    　　瞻仰信徒们朝拜的圣地
</p>
<p style="box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &quot;Microsoft Yahei&quot;, &quot;Helvetica Neue&quot;, Helvetica, &quot;Hiragino Sans GB&quot;, &quot;WenQuanYi Micro Hei&quot;, sans-serif; font-size: 18px; white-space: normal; background-color: rgb(255, 255, 255);">
    　　感受他们的虔诚
</p>
<p style="box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &quot;Microsoft Yahei&quot;, &quot;Helvetica Neue&quot;, Helvetica, &quot;Hiragino Sans GB&quot;, &quot;WenQuanYi Micro Hei&quot;, sans-serif; font-size: 18px; white-space: normal; background-color: rgb(255, 255, 255);">
    　　<img src="http://nbot-pub.nosdn.127.net/b569f467996d7b9b7c15345ed3a0feff.jpeg" style="box-sizing: border-box; border: 0px; vertical-align: middle; max-width: 100%; margin: 20px 0px;"/>
</p>
<p style="box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &quot;Microsoft Yahei&quot;, &quot;Helvetica Neue&quot;, Helvetica, &quot;Hiragino Sans GB&quot;, &quot;WenQuanYi Micro Hei&quot;, sans-serif; font-size: 18px; white-space: normal; background-color: rgb(255, 255, 255);">
    　　等一个人
</p>
<p style="box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &quot;Microsoft Yahei&quot;, &quot;Helvetica Neue&quot;, Helvetica, &quot;Hiragino Sans GB&quot;, &quot;WenQuanYi Micro Hei&quot;, sans-serif; font-size: 18px; white-space: normal; background-color: rgb(255, 255, 255);">
    　　陪我去<span style="box-sizing: border-box; font-weight: 700;">八廓街</span>
</p>
<p style="box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &quot;Microsoft Yahei&quot;, &quot;Helvetica Neue&quot;, Helvetica, &quot;Hiragino Sans GB&quot;, &quot;WenQuanYi Micro Hei&quot;, sans-serif; font-size: 18px; white-space: normal; background-color: rgb(255, 255, 255);">
    　　观察和体验独特的藏地民俗和文化
</p>
<p style="box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &quot;Microsoft Yahei&quot;, &quot;Helvetica Neue&quot;, Helvetica, &quot;Hiragino Sans GB&quot;, &quot;WenQuanYi Micro Hei&quot;, sans-serif; font-size: 18px; white-space: normal; background-color: rgb(255, 255, 255);">
    　　<img src="http://nbot-pub.nosdn.127.net/e19d4eb88c0ed9f00c1cb554ac7a67fb.jpeg" style="box-sizing: border-box; border: 0px; vertical-align: middle; max-width: 100%; margin: 20px 0px;"/>
</p>
<p style="box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &quot;Microsoft Yahei&quot;, &quot;Helvetica Neue&quot;, Helvetica, &quot;Hiragino Sans GB&quot;, &quot;WenQuanYi Micro Hei&quot;, sans-serif; font-size: 18px; white-space: normal; background-color: rgb(255, 255, 255);">
    　　<span style="box-sizing: border-box; font-weight: 700;">By- 王寰</span>
</p>
<p style="box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &quot;Microsoft Yahei&quot;, &quot;Helvetica Neue&quot;, Helvetica, &quot;Hiragino Sans GB&quot;, &quot;WenQuanYi Micro Hei&quot;, sans-serif; font-size: 18px; white-space: normal; background-color: rgb(255, 255, 255);">
    　　等一个人
</p>
<p style="box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &quot;Microsoft Yahei&quot;, &quot;Helvetica Neue&quot;, Helvetica, &quot;Hiragino Sans GB&quot;, &quot;WenQuanYi Micro Hei&quot;, sans-serif; font-size: 18px; white-space: normal; background-color: rgb(255, 255, 255);">
    　　陪我去<span style="box-sizing: border-box; font-weight: 700;">雅鲁藏布大峡谷</span>
</p>
<p style="box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &quot;Microsoft Yahei&quot;, &quot;Helvetica Neue&quot;, Helvetica, &quot;Hiragino Sans GB&quot;, &quot;WenQuanYi Micro Hei&quot;, sans-serif; font-size: 18px; white-space: normal; background-color: rgb(255, 255, 255);">
    　　接收上苍赐予的好运
</p>
<p style="box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &quot;Microsoft Yahei&quot;, &quot;Helvetica Neue&quot;, Helvetica, &quot;Hiragino Sans GB&quot;, &quot;WenQuanYi Micro Hei&quot;, sans-serif; font-size: 18px; white-space: normal; background-color: rgb(255, 255, 255);">
    　　<img src="http://nbot-pub.nosdn.127.net/51cffb6a92ab113e3e9cfa8be4fe2cfa.jpeg" style="box-sizing: border-box; border: 0px; vertical-align: middle; max-width: 100%; margin: 20px 0px;"/>
</p>
<p style="box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &quot;Microsoft Yahei&quot;, &quot;Helvetica Neue&quot;, Helvetica, &quot;Hiragino Sans GB&quot;, &quot;WenQuanYi Micro Hei&quot;, sans-serif; font-size: 18px; white-space: normal; background-color: rgb(255, 255, 255);">
    　　<span style="box-sizing: border-box; font-weight: 700;">By- 黄崇峰</span><br style="box-sizing: border-box;"/>
</p>
<p style="box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &quot;Microsoft Yahei&quot;, &quot;Helvetica Neue&quot;, Helvetica, &quot;Hiragino Sans GB&quot;, &quot;WenQuanYi Micro Hei&quot;, sans-serif; font-size: 18px; white-space: normal; background-color: rgb(255, 255, 255);">
    　　等一个人
</p>
<p style="box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &quot;Microsoft Yahei&quot;, &quot;Helvetica Neue&quot;, Helvetica, &quot;Hiragino Sans GB&quot;, &quot;WenQuanYi Micro Hei&quot;, sans-serif; font-size: 18px; white-space: normal; background-color: rgb(255, 255, 255);">
    　　陪我去<span style="box-sizing: border-box; font-weight: 700;">扎什伦布寺</span>
</p>
<p style="box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &quot;Microsoft Yahei&quot;, &quot;Helvetica Neue&quot;, Helvetica, &quot;Hiragino Sans GB&quot;, &quot;WenQuanYi Micro Hei&quot;, sans-serif; font-size: 18px; white-space: normal; background-color: rgb(255, 255, 255);">
    　　在太阳雨的沐浴下
</p>
<p style="box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &quot;Microsoft Yahei&quot;, &quot;Helvetica Neue&quot;, Helvetica, &quot;Hiragino Sans GB&quot;, &quot;WenQuanYi Micro Hei&quot;, sans-serif; font-size: 18px; white-space: normal; background-color: rgb(255, 255, 255);">
    　　让内心回归平静
</p>
<p style="box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &quot;Microsoft Yahei&quot;, &quot;Helvetica Neue&quot;, Helvetica, &quot;Hiragino Sans GB&quot;, &quot;WenQuanYi Micro Hei&quot;, sans-serif; font-size: 18px; white-space: normal; background-color: rgb(255, 255, 255);">
    　　<img src="http://nbot-pub.nosdn.127.net/07ba4a0920a53b0ce836fb4d659b023f.jpeg" style="box-sizing: border-box; border: 0px; vertical-align: middle; max-width: 100%; margin: 20px 0px;"/>
</p>
<p style="box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &quot;Microsoft Yahei&quot;, &quot;Helvetica Neue&quot;, Helvetica, &quot;Hiragino Sans GB&quot;, &quot;WenQuanYi Micro Hei&quot;, sans-serif; font-size: 18px; white-space: normal; background-color: rgb(255, 255, 255);">
    　　等一个人
</p>
<p style="box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &quot;Microsoft Yahei&quot;, &quot;Helvetica Neue&quot;, Helvetica, &quot;Hiragino Sans GB&quot;, &quot;WenQuanYi Micro Hei&quot;, sans-serif; font-size: 18px; white-space: normal; background-color: rgb(255, 255, 255);">
    　　陪我去<span style="box-sizing: border-box; font-weight: 700;">然乌湖</span>
</p>
<p style="box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &quot;Microsoft Yahei&quot;, &quot;Helvetica Neue&quot;, Helvetica, &quot;Hiragino Sans GB&quot;, &quot;WenQuanYi Micro Hei&quot;, sans-serif; font-size: 18px; white-space: normal; background-color: rgb(255, 255, 255);">
    　　看蓝天白云一路相伴
</p>
<p style="box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &quot;Microsoft Yahei&quot;, &quot;Helvetica Neue&quot;, Helvetica, &quot;Hiragino Sans GB&quot;, &quot;WenQuanYi Micro Hei&quot;, sans-serif; font-size: 18px; white-space: normal; background-color: rgb(255, 255, 255);">
    　　目不暇接
</p>
<p style="box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &quot;Microsoft Yahei&quot;, &quot;Helvetica Neue&quot;, Helvetica, &quot;Hiragino Sans GB&quot;, &quot;WenQuanYi Micro Hei&quot;, sans-serif; font-size: 18px; white-space: normal; background-color: rgb(255, 255, 255);">
    　　<img src="http://nbot-pub.nosdn.127.net/0aa0288cec427d4278c4042094c62c6a.jpeg" style="box-sizing: border-box; border: 0px; vertical-align: middle; max-width: 100%; margin: 20px 0px;"/>
</p>
<p style="box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &quot;Microsoft Yahei&quot;, &quot;Helvetica Neue&quot;, Helvetica, &quot;Hiragino Sans GB&quot;, &quot;WenQuanYi Micro Hei&quot;, sans-serif; font-size: 18px; white-space: normal; background-color: rgb(255, 255, 255);">
    　　等一个人
</p>
<p style="box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &quot;Microsoft Yahei&quot;, &quot;Helvetica Neue&quot;, Helvetica, &quot;Hiragino Sans GB&quot;, &quot;WenQuanYi Micro Hei&quot;, sans-serif; font-size: 18px; white-space: normal; background-color: rgb(255, 255, 255);">
    　　陪我去<span style="box-sizing: border-box; font-weight: 700;">古格王国都城遗址</span>
</p>
<p style="box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &quot;Microsoft Yahei&quot;, &quot;Helvetica Neue&quot;, Helvetica, &quot;Hiragino Sans GB&quot;, &quot;WenQuanYi Micro Hei&quot;, sans-serif; font-size: 18px; white-space: normal; background-color: rgb(255, 255, 255);">
    　　通过那些仅存的壁画和古老沧桑的土林建筑
</p>
<p style="box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &quot;Microsoft Yahei&quot;, &quot;Helvetica Neue&quot;, Helvetica, &quot;Hiragino Sans GB&quot;, &quot;WenQuanYi Micro Hei&quot;, sans-serif; font-size: 18px; white-space: normal; background-color: rgb(255, 255, 255);">
    　　想象它曾经的辉煌
</p>
<p style="box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &quot;Microsoft Yahei&quot;, &quot;Helvetica Neue&quot;, Helvetica, &quot;Hiragino Sans GB&quot;, &quot;WenQuanYi Micro Hei&quot;, sans-serif; font-size: 18px; white-space: normal; background-color: rgb(255, 255, 255);">
    　　<img src="http://nbot-pub.nosdn.127.net/1e75c7484c52ed91ef265e44623a6f53.jpeg" style="box-sizing: border-box; border: 0px; vertical-align: middle; max-width: 100%; margin: 20px 0px;"/>
</p>
<p style="box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &quot;Microsoft Yahei&quot;, &quot;Helvetica Neue&quot;, Helvetica, &quot;Hiragino Sans GB&quot;, &quot;WenQuanYi Micro Hei&quot;, sans-serif; font-size: 18px; white-space: normal; background-color: rgb(255, 255, 255);">
    　　等一个人
</p>
<p style="box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &quot;Microsoft Yahei&quot;, &quot;Helvetica Neue&quot;, Helvetica, &quot;Hiragino Sans GB&quot;, &quot;WenQuanYi Micro Hei&quot;, sans-serif; font-size: 18px; white-space: normal; background-color: rgb(255, 255, 255);">
    　　陪我去<span style="box-sizing: border-box; font-weight: 700;">托林寺</span>
</p>
<p style="box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &quot;Microsoft Yahei&quot;, &quot;Helvetica Neue&quot;, Helvetica, &quot;Hiragino Sans GB&quot;, &quot;WenQuanYi Micro Hei&quot;, sans-serif; font-size: 18px; white-space: normal; background-color: rgb(255, 255, 255);">
    　　深深凝视每一笔被岁月洗练的线条和色彩
</p>
<p style="box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &quot;Microsoft Yahei&quot;, &quot;Helvetica Neue&quot;, Helvetica, &quot;Hiragino Sans GB&quot;, &quot;WenQuanYi Micro Hei&quot;, sans-serif; font-size: 18px; white-space: normal; background-color: rgb(255, 255, 255);">
    　　沉浸在一种宁静超然的宗教情味中
</p>
<p style="box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &quot;Microsoft Yahei&quot;, &quot;Helvetica Neue&quot;, Helvetica, &quot;Hiragino Sans GB&quot;, &quot;WenQuanYi Micro Hei&quot;, sans-serif; font-size: 18px; white-space: normal; background-color: rgb(255, 255, 255);">
    　　<img src="http://nbot-pub.nosdn.127.net/d10f364a24b92f8f1b3cbdb0dbe5353e.jpeg" style="box-sizing: border-box; border: 0px; vertical-align: middle; max-width: 100%; margin: 20px 0px;"/>
</p>
<p style="box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &quot;Microsoft Yahei&quot;, &quot;Helvetica Neue&quot;, Helvetica, &quot;Hiragino Sans GB&quot;, &quot;WenQuanYi Micro Hei&quot;, sans-serif; font-size: 18px; white-space: normal; background-color: rgb(255, 255, 255);">
    　　<span style="box-sizing: border-box; font-weight: 700;">By- 海边一人</span>
</p>
<p style="box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &quot;Microsoft Yahei&quot;, &quot;Helvetica Neue&quot;, Helvetica, &quot;Hiragino Sans GB&quot;, &quot;WenQuanYi Micro Hei&quot;, sans-serif; font-size: 18px; white-space: normal; background-color: rgb(255, 255, 255);">
    　　每个人都有一个旅行梦<br style="box-sizing: border-box;"/>
</p>
<p style="box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &quot;Microsoft Yahei&quot;, &quot;Helvetica Neue&quot;, Helvetica, &quot;Hiragino Sans GB&quot;, &quot;WenQuanYi Micro Hei&quot;, sans-serif; font-size: 18px; white-space: normal; background-color: rgb(255, 255, 255);">
    　　梦的尽头在西藏
</p>
<p style="box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &quot;Microsoft Yahei&quot;, &quot;Helvetica Neue&quot;, Helvetica, &quot;Hiragino Sans GB&quot;, &quot;WenQuanYi Micro Hei&quot;, sans-serif; font-size: 18px; white-space: normal; background-color: rgb(255, 255, 255);">
    　　在青藏高原
</p>
<p style="box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &quot;Microsoft Yahei&quot;, &quot;Helvetica Neue&quot;, Helvetica, &quot;Hiragino Sans GB&quot;, &quot;WenQuanYi Micro Hei&quot;, sans-serif; font-size: 18px; white-space: normal; background-color: rgb(255, 255, 255);">
    　　<img src="http://nbot-pub.nosdn.127.net/4b84f3505b59b28f5b74ef830b20bb65.jpeg" style="box-sizing: border-box; border: 0px; vertical-align: middle; max-width: 100%; margin: 20px 0px;"/>
</p>
<p style="box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &quot;Microsoft Yahei&quot;, &quot;Helvetica Neue&quot;, Helvetica, &quot;Hiragino Sans GB&quot;, &quot;WenQuanYi Micro Hei&quot;, sans-serif; font-size: 18px; white-space: normal; background-color: rgb(255, 255, 255);">
    　　那里有雪山青草美丽的喇嘛庙<br style="box-sizing: border-box;"/>
</p>
<p style="box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &quot;Microsoft Yahei&quot;, &quot;Helvetica Neue&quot;, Helvetica, &quot;Hiragino Sans GB&quot;, &quot;WenQuanYi Micro Hei&quot;, sans-serif; font-size: 18px; white-space: normal; background-color: rgb(255, 255, 255);">
    　　康巴汉子骑马走在大草原上
</p>
<p style="box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &quot;Microsoft Yahei&quot;, &quot;Helvetica Neue&quot;, Helvetica, &quot;Hiragino Sans GB&quot;, &quot;WenQuanYi Micro Hei&quot;, sans-serif; font-size: 18px; white-space: normal; background-color: rgb(255, 255, 255);">
    　　仓央嘉措的情诗被眼神清澈的藏族人传唱
</p>
<p style="box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &quot;Microsoft Yahei&quot;, &quot;Helvetica Neue&quot;, Helvetica, &quot;Hiragino Sans GB&quot;, &quot;WenQuanYi Micro Hei&quot;, sans-serif; font-size: 18px; white-space: normal; background-color: rgb(255, 255, 255);">
    　　玛吉阿米的脸庞犹如月亮
</p>
<p style="box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &quot;Microsoft Yahei&quot;, &quot;Helvetica Neue&quot;, Helvetica, &quot;Hiragino Sans GB&quot;, &quot;WenQuanYi Micro Hei&quot;, sans-serif; font-size: 18px; white-space: normal; background-color: rgb(255, 255, 255);">
    　　<img src="http://nbot-pub.nosdn.127.net/a34f2bf0b5a9af2a09c9a3de47cd7de0.jpeg" style="box-sizing: border-box; border: 0px; vertical-align: middle; max-width: 100%; margin: 20px 0px;"/>
</p>
<p style="box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &quot;Microsoft Yahei&quot;, &quot;Helvetica Neue&quot;, Helvetica, &quot;Hiragino Sans GB&quot;, &quot;WenQuanYi Micro Hei&quot;, sans-serif; font-size: 18px; white-space: normal; background-color: rgb(255, 255, 255);">
    　　雪山下每年三四月
</p>
<p style="box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &quot;Microsoft Yahei&quot;, &quot;Helvetica Neue&quot;, Helvetica, &quot;Hiragino Sans GB&quot;, &quot;WenQuanYi Micro Hei&quot;, sans-serif; font-size: 18px; white-space: normal; background-color: rgb(255, 255, 255);">
    　　盛开出世上最艳丽的桃花
</p>
<p style="box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &quot;Microsoft Yahei&quot;, &quot;Helvetica Neue&quot;, Helvetica, &quot;Hiragino Sans GB&quot;, &quot;WenQuanYi Micro Hei&quot;, sans-serif; font-size: 18px; white-space: normal; background-color: rgb(255, 255, 255);">
    　　谁说花没有魂<br style="box-sizing: border-box;"/>
</p>
<p style="box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &quot;Microsoft Yahei&quot;, &quot;Helvetica Neue&quot;, Helvetica, &quot;Hiragino Sans GB&quot;, &quot;WenQuanYi Micro Hei&quot;, sans-serif; font-size: 18px; white-space: normal; background-color: rgb(255, 255, 255);">
    　　桃花宛若你点燃的<br style="box-sizing: border-box;"/>
</p>
<p style="box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &quot;Microsoft Yahei&quot;, &quot;Helvetica Neue&quot;, Helvetica, &quot;Hiragino Sans GB&quot;, &quot;WenQuanYi Micro Hei&quot;, sans-serif; font-size: 18px; white-space: normal; background-color: rgb(255, 255, 255);">
    　　一树酥油灯
</p>
<p style="box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &quot;Microsoft Yahei&quot;, &quot;Helvetica Neue&quot;, Helvetica, &quot;Hiragino Sans GB&quot;, &quot;WenQuanYi Micro Hei&quot;, sans-serif; font-size: 18px; white-space: normal; background-color: rgb(255, 255, 255);">
    　　<img src="http://nbot-pub.nosdn.127.net/09f978602706aa5b2a809b0bde8ca8ed.jpeg" style="box-sizing: border-box; border: 0px; vertical-align: middle; max-width: 100%; margin: 20px 0px;"/>
</p>
<p style="box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &quot;Microsoft Yahei&quot;, &quot;Helvetica Neue&quot;, Helvetica, &quot;Hiragino Sans GB&quot;, &quot;WenQuanYi Micro Hei&quot;, sans-serif; font-size: 18px; white-space: normal; background-color: rgb(255, 255, 255);">
    　　山顶的雪花与金川河谷的梨花竞相争艳
</p>
<p style="box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &quot;Microsoft Yahei&quot;, &quot;Helvetica Neue&quot;, Helvetica, &quot;Hiragino Sans GB&quot;, &quot;WenQuanYi Micro Hei&quot;, sans-serif; font-size: 18px; white-space: normal; background-color: rgb(255, 255, 255);">
    　　山花乱飞，梨花闹春
</p>
<p style="box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &quot;Microsoft Yahei&quot;, &quot;Helvetica Neue&quot;, Helvetica, &quot;Hiragino Sans GB&quot;, &quot;WenQuanYi Micro Hei&quot;, sans-serif; font-size: 18px; white-space: normal; background-color: rgb(255, 255, 255);">
    　　雾里看花闻花香……
</p>
<p style="box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &quot;Microsoft Yahei&quot;, &quot;Helvetica Neue&quot;, Helvetica, &quot;Hiragino Sans GB&quot;, &quot;WenQuanYi Micro Hei&quot;, sans-serif; font-size: 18px; white-space: normal; background-color: rgb(255, 255, 255);">
    　　<span style="box-sizing: border-box; font-weight: 700;"><br style="box-sizing: border-box;"/></span>
</p>
<p style="box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &quot;Microsoft Yahei&quot;, &quot;Helvetica Neue&quot;, Helvetica, &quot;Hiragino Sans GB&quot;, &quot;WenQuanYi Micro Hei&quot;, sans-serif; font-size: 18px; white-space: normal; background-color: rgb(255, 255, 255);">
    　　<img src="http://nbot-pub.nosdn.127.net/fd626a2adfc3508e1a47d20420bf33bd.jpeg" style="box-sizing: border-box; border: 0px; vertical-align: middle; max-width: 100%; margin: 20px 0px;"/>
</p>
<p style="box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &quot;Microsoft Yahei&quot;, &quot;Helvetica Neue&quot;, Helvetica, &quot;Hiragino Sans GB&quot;, &quot;WenQuanYi Micro Hei&quot;, sans-serif; font-size: 18px; white-space: normal; background-color: rgb(255, 255, 255);">
    　　<span style="box-sizing: border-box; font-weight: 700;">等你，</span>
</p>
<p style="box-sizing: border-box; margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &quot;Microsoft Yahei&quot;, &quot;Helvetica Neue&quot;, Helvetica, &quot;Hiragino Sans GB&quot;, &quot;WenQuanYi Micro Hei&quot;, sans-serif; font-size: 18px; white-space: normal; background-color: rgb(255, 255, 255);">
    　　<span style="box-sizing: border-box; font-weight: 700;">陪我去西藏。</span>
</p>
<p>
    <br/>
</p>
html;
$html2json=new html2json();
print_r($html2json->html2jsonarr($htmlstring));
echo $html2json->html2jsonobj($htmlstring);

*/
