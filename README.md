# CodeIgniter 2.0.1 for SAE
===================

## 此为何物

codeigniter-for-sae是CodeIgniter 2.0.1在新浪云计算平台SAE上的移植，未来可能随CodeIgniter版本而升级。

## 如何使用

*   您需要将自己网站中的application当中的内容，复制至相应的位置
*   您需要在application/config/config.php中，初始化数个与SAE相关的变量，以使用相应的SAE功能，如Storage、SQL等；其他的配置暂且无需作出变化
*   迁移您的数据库数据、其它资源数据、缓存数据等

## 项目进展

现阶段项目还处于初级阶段，但基本的功能已在一定程度上可用：
*   SQL数据库
*   缓存
*   文件上传
*   日志
*   其它和文件I/O大致无关的功能

仍然不能用或还不能确定的功能：
*   E-mail库。SAE仅仅提供SMTP发送邮件，因此该库目前尚不可用。如果有发送邮件的需求，请使用email_helper。
*   Image_lib。未经测试。
*   directory和path两个helper都检查的是本地目录，仍未修复。
*   captcha_helper使用本地目录作为验证码图片存储目录，仍未修复。
*   可能还有其它未意识到的库。

## 注意事项

*   在已经可用的功能中，所有的文件写操作以及与写相关的读操作，都将最终指向Storage，而不是本地目录，因为本地目录不可写。例如CI的日志输出将输出至Storage的相应目录树下。
*   缓存可选范围较大，但官方只推荐使用Memcache作为缓存
*   请不要轻易修改数据库相关配置

=============
## WHAT IS IT

codeigniter-for-sae is a port of CodeIgniter 2.0.1, the PHP framewrok, for sina SAE platform, and may upgrade to newer ones if official version does.

## HOW TO USE

*   Upload your files inside your original application folder to this one's.
*   Initlize several variables in application/config/config.php about SAE, or you cannot use functions such as Storage, SQL, etc. 
*   Move other resource files, js files.

## CURRENT STATUS

This port is still in development, but most of the functions are work fine already:

*   SQL
*   Memcache
*   Log lib
*   Other functions without local I/O

Some functions not working or not sure are:

*   E-mail lib. SAE only supports SMTP for mails. Use email_helper instead.
*   Image_lib. Not tested.
*   directory_helper and path_helper. They're designed for local directories. Still not fixed.
*   captcha_helper. It use local directory for cache. Still not fixed.
*   Others I may don't know.

## CAUTIOUS

*   All the working functions use Storage for file I/O, not local ones. SAE disables local file write. Log output will turn to Storage, notably.
*   You should consider Memcache for cache first. 
*   Be careful of the database config. Don't edit unless you know what you doing.


