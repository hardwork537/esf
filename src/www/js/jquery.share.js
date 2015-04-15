//jQuery.share
; (function(a) {
    var b = function(a, b, c, d) {
            return {
                name: a,
                alias: b,
                url: c,
                pos: d
            }
        },
        c = function(a, b) {
            var c = b.title,
                d = b.url,
                e = b.content,
                f = b.picture,
                g = a.name,
                h = b.appKey[a.name] || "",
                i,
                j,
                k,
                l;
            j = " - " + e;
            if (e == null || c.indexOf(e) != -1 || e.length <= 1) j = "";
            "t163" == g ? i = encodeURIComponent(c + j + " " + d) : "tsina" == g || "tsohu" == g || "tqq" == g ? i = encodeURIComponent(c + j) : i = encodeURIComponent(c),
                l = encodeURIComponent(e),
                k = a.url.replace("{0}", encodeURIComponent(d)).replace("{1}", i).replace("{2}", l).replace("{3}", encodeURIComponent(f)).replace("{-1}", h);
            return k
        };
    a.ShareTo = function() {},
		a.ShareTo.shares = {            
			1 : b("tsohu", "搜狐微博", "http://t.sohu.com/third/post.jsp?&url={0}&title={1}&content=utf-8&pic={3}", 3),
            2 : b("tsina", "新浪微博", "http://service.weibo.com/share/share.php?url={0}&source=bookmark&title={1}&appkey={-1}&pic={3}", 1),
            3 : b("tqq", "腾讯微博", "http://v.t.qq.com/share/share.php?title={1}&url={0}&pic={3}", 2),
            4 : b("t163", "网易微博", "http://t.163.com/article/user/checkLogin.do?source=Passit&info={1}&pic={3}", 4),
            5 : b("qqkj", "QQ空间", "http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url={0}&title={1}", 9),
            6 : b("rrw", "人人网", "http://share.renren.com/share/buttonshare.do?link={0}&title={1}", 7),
            7 : b("kxw", "开心网", "http://www.kaixin001.com/repaste/bshare.php?rtitle={1}&rurl={0}&rcontent={2}", 8)
        },
        a.ShareTo.defaults = {
            showIndex: [1, 2, 3, 4, 5, 6, 7],
            imgUrl: "icon_share.png",
            imgSize: 16,
            liMargin: 4,
            showLabel: !0,
            url: document.location.href,
            title: document.title,
            content: function() {
                var b = [];
                a("meta").each(function() {
                    var c = a(this);
                    c.attr("name") && c.attr("name").toLowerCase() == "description" && b.push(c.attr("content"));
                });
                return b.join("-")
            } (),
            picture: "",
            appKey: {
                tsina: "",
                tqq: ""
            }
        },
        a.fn.shareTo = function(b) {
            var d = a.extend(!0, {},
                    a.ShareTo.defaults, b || {}),
                e,
                f,
                g,
                h,
                i = d.showIndex.length;
            for (g = 0; g < i; g++) {
                h = d.showIndex[g],
                    f = a.ShareTo.shares[h];
                if (!f) continue;
                a(this).children("li").eq(g).find('a').attr('href', c(f, d))
            }
        }
})(jQuery);