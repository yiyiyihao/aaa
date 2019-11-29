//base载入
Do.add('base', {
    //path: 'http://dev.bi.com/static/base/js/apibase.js',
	path: Config.baseDir + 'base.js',
	requires: ['dialog','lazyload','md5']
});
Do.add('dialogcss',
{
    path : Config.baseDir + 'layer/theme/default/layer.css'
});
Do.add('dialog',
{
    path : Config.baseDir + 'layer/layer.js',
	requires : ['dialogcss']
});
Do.add('md5',
{
    path : Config.baseDir + 'md5.js',
});
Do.add('lazyload',
{
    path : Config.baseDir + 'lazyload.min.js',
});

//调试函数
function debug(obj) {
    if (typeof console != 'undefined') {
        console.log(obj);
    }
}