(function(){tinymce.create('tinymce.plugins.mnUserAvatar',{init:function(c,d){c.addCommand('mceWpUserAvatar',function(){c.windowManager.open({file:ajaxurl+'?action=mn_user_avatar_tinymce',width:500,height:360,inline:1},{plugin_url:d})});c.addButton('mnUserAvatar',{title:'Insert User Avatar',cmd:'mceWpUserAvatar',image:d+'/../../images/mnua-20x20.png'});c.onNodeChange.add(function(a,b,n){b.setActive('mnUserAvatar',n.nodeName=='IMG')})},createControl:function(n,a){return null},getInfo:function(){return{longname:'User Avatar',author:'Bangbay Siboliban',authorurl:'http://siboliban.org/',infourl:'http://mtaandao.org/plugins/mn-user-avatar/',version:"1.9.13"}}});tinymce.PluginManager.add('mnUserAvatar',tinymce.plugins.mnUserAvatar)})();