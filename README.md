codeigniter-for-sae
===================

��Ϊ����

codeigniter-for-sae��CodeIgniter 2.0.1�������Ƽ���ƽ̨SAE�ϵ���ֲ��δ��������CodeIgniter�汾��������

���ʹ��

1.����Ҫ���Լ���վ�е�application���е����ݣ���������Ӧ��λ��
2.����Ҫ��application/config/config.php�У���ʼ��������SAE��صı�������ʹ����Ӧ��SAE���ܣ���Storage��SQL�ȣ������������������������仯
3.Ǩ���������ݿ����ݡ�������Դ���ݡ��������ݵ�

��Ŀ��չ

�ֽ׶���Ŀ�����ڳ����׶Σ��������Ĺ�������һ���̶��Ͽ��ã�
1.SQL���ݿ�
2.����
3.�ļ��ϴ�
4.��־
5.�������ļ�I/O�����޹صĹ���

��Ȼ�����û򻹲���ȷ���Ĺ��ܣ�
1.E-mail�⡣SAE�����ṩSMTP�����ʼ�����˸ÿ�Ŀǰ�в����á�����з����ʼ���������ʹ��email_helper��
2.Zip�⡣δ�����ԡ�
3.Image_lib��δ�����ԡ�
4.directory��path����helper�������Ǳ���Ŀ¼����δ�޸���
5.captcha_helperʹ�ñ���Ŀ¼��Ϊ��֤��ͼƬ�洢Ŀ¼����δ�޸���
6.���ܻ�������δ��ʶ���Ŀ⡣

ע������

1.���Ѿ����õĹ����У����е��ļ�д�����Լ���д��صĶ���������������ָ��Storage�������Ǳ���Ŀ¼����Ϊ����Ŀ¼����д������CI����־����������Storage����ӦĿ¼���¡�
2.�����ѡ��Χ�ϴ󣬵��ٷ�ֻ�Ƽ�ʹ��Memcache��Ϊ����
3.�벻Ҫ�����޸����ݿ��������

=============
WHAT IS IT

codeigniter-for-sae is a port of CodeIgniter 2.0.1, the PHP framewrok, for sina SAE platform, and may upgrade to newer ones if official version does.

HOW TO USE

1.Upload your files inside your original application folder to this one's.
2.Initlize several variables in application/config/config.php about SAE, or you cannot use functions such as Storage, SQL, etc. 
3.Move other resource files, js files.

CURRENT STATUS

This port is still in development, but most of the functions are work fine already:

1.SQL
2.Memcache
3.Log lib
4.Other functions without local I/O

Some functions not working or not sure are:

1.E-mail lib. SAE only supports SMTP for mails. Use email_helper instead.
2.Zip lib. Lots of local I/O in it.
3.Image_lib. Not tested.
4.directory_helper and path_helper. They're designed for local directories. Still not fixed.
5.captcha_helper. It use local directory for cache. Still not fixed.
6.Others I may don't know.

CAUTIOUS

1.All the working functions use Storage for file I/O, not local ones. SAE disables local file write. Log output will turn to Storage, notably.
2.You should consider Memcache for cache first. 
3.Be careful of the database config. Don't edit unless you know what you doing.

CodeIgniter 2.0.1 for SAE