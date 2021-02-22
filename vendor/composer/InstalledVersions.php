<?php











namespace Composer;

use Composer\Autoload\ClassLoader;
use Composer\Semver\VersionParser;






class InstalledVersions
{
private static $installed = array (
  'root' => 
  array (
    'pretty_version' => 'dev-master',
    'version' => 'dev-master',
    'aliases' => 
    array (
      0 => '0.8.x-dev',
    ),
    'reference' => NULL,
    'name' => 'graphql-api/graphql-api-for-wp',
  ),
  'versions' => 
  array (
    'brain/cortex' => 
    array (
      'pretty_version' => '1.0.0-alpha.7',
      'version' => '1.0.0.0-alpha7',
      'aliases' => 
      array (
      ),
      'reference' => '0f33ad8578fa051ab5e46e14c9478df4d728e49a',
    ),
    'composer/semver' => 
    array (
      'pretty_version' => '3.2.4',
      'version' => '3.2.4.0',
      'aliases' => 
      array (
      ),
      'reference' => 'a02fdf930a3c1c3ed3a49b5f63859c0c20e10464',
    ),
    'getpop/access-control' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => 'b0c7bfb89b4a7104ec9f503beee20d418de0977c',
    ),
    'getpop/api' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => '16e0e669aebc9bec78f74cf29b2771db10610959',
    ),
    'getpop/api-clients' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => '4848d5521134f22b3b5fe88753a73e62a237a5a7',
    ),
    'getpop/api-endpoints' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => '68605433a83b693f90c353b9e8c9f94f02973b67',
    ),
    'getpop/api-endpoints-for-wp' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => '8a59f0863f9f3defc94ed0c9c59bde26fcbcb6fc',
    ),
    'getpop/api-graphql' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => 'fd673afc981f7a6eb2e914938bf20214dd57f0aa',
    ),
    'getpop/api-mirrorquery' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => '8a65fe6a00f8e4c6ebe3b3ff1d725423e0b819cb',
    ),
    'getpop/cache-control' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => 'dd4d61d2c6f91f153db2e640d51cae8652b47739',
    ),
    'getpop/component-model' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => 'fd86088d8d2e7777765669a513182b7339cb2850',
    ),
    'getpop/definitions' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => '0c5491f1dc835c844c43ed6d4a95296dbdab1f3f',
    ),
    'getpop/engine' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => '2045f9c37a4142b8529b98604fdd83996e393cf8',
    ),
    'getpop/engine-wp' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => '1ad82fb5793188e0e4b74d33a5d28543addb054a',
    ),
    'getpop/field-query' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => 'd47674914ad166539f3c59b721351d7d7ee0308c',
    ),
    'getpop/guzzle-helpers' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => 'df2e73aca7132aa6d702dba33dfc2e9c8c7726b8',
    ),
    'getpop/hooks' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => 'd63fd9c774232802ce97c37152666f3dd8f096b8',
    ),
    'getpop/hooks-wp' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => 'ac4133ac1562685f6245f73df04254f1fde061cd',
    ),
    'getpop/loosecontracts' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => '4e4a802a93bb26447d1f345cb33f8bc6c79e122a',
    ),
    'getpop/mandatory-directives-by-configuration' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => '3e4dba8527ea237038932ea047228c61f3231bf1',
    ),
    'getpop/migrate-api' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => 'd620def2651b547f1407134162b88aa92cc0844f',
    ),
    'getpop/migrate-api-graphql' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => '97ac259b5133b0e2cd00e173103ff648c529856e',
    ),
    'getpop/migrate-component-model' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => 'f5467f0b4e6129056c8ca80588701f23389fac72',
    ),
    'getpop/migrate-engine' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => '9b38820f3dadb0115e1cee0bcb340c9c22b4edde',
    ),
    'getpop/migrate-engine-wp' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => '9b0cc4deb70e546dc35e952c0d0454bdb172d7af',
    ),
    'getpop/modulerouting' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => '8db70cdde503a2157ea178333f61fea07838b7eb',
    ),
    'getpop/query-parsing' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => 'b5af46d7a4ad60677b6dd529361ad7835ba812bb',
    ),
    'getpop/root' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => 'f4d79d59f12dfffb5aa93ae247193dbf7d28f38b',
    ),
    'getpop/routing' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => 'fb99652ddf70a95adbd51a4f1dab104aea52ef86',
    ),
    'getpop/routing-wp' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => '4568faa735e118efd1c1d6f846a8eff439855f85',
    ),
    'getpop/translation' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => 'af032429914bd14949a6fa605e1b9106abc25b3b',
    ),
    'getpop/translation-wp' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => 'a8f9f1de6293fa316169b6ef4618b31763926659',
    ),
    'graphql-api/graphql-api-for-wp' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => NULL,
    ),
    'graphql-api/markdown-convertor' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => 'be48979bcd9e76d048aa8ac3dc37a6e70178dd45',
    ),
    'graphql-by-pop/graphql-clients-for-wp' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => '772abc31ccea87ad96bb7a936a0767f41dc29db5',
    ),
    'graphql-by-pop/graphql-endpoint-for-wp' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => '8d55a5c41f76555c74643ebba058cb4d24938261',
    ),
    'graphql-by-pop/graphql-parser' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => 'e826bd064818552337610cf3c1a8507148085091',
    ),
    'graphql-by-pop/graphql-query' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => 'e557827d340e372d5c0c5ddc345153b2ca8f6740',
    ),
    'graphql-by-pop/graphql-request' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => '53e3c733aa5e2f2f77cd8d0b5e02d1b905eb3f9b',
    ),
    'graphql-by-pop/graphql-server' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => '5b2e1579cf843f42a507b0f51df29c851eb5b56f',
    ),
    'guzzlehttp/guzzle' => 
    array (
      'pretty_version' => '6.5.5',
      'version' => '6.5.5.0',
      'aliases' => 
      array (
      ),
      'reference' => '9d4290de1cfd701f38099ef7e183b64b4b7b0c5e',
    ),
    'guzzlehttp/promises' => 
    array (
      'pretty_version' => '1.4.0',
      'version' => '1.4.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '60d379c243457e073cff02bc323a2a86cb355631',
    ),
    'guzzlehttp/psr7' => 
    array (
      'pretty_version' => '1.7.0',
      'version' => '1.7.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '53330f47520498c0ae1f61f7e2c90f55690c06a3',
    ),
    'jrfnl/php-cast-to-type' => 
    array (
      'pretty_version' => '2.0.1',
      'version' => '2.0.1.0',
      'aliases' => 
      array (
      ),
      'reference' => '0e4266d32387f34d13dfbb50506b1f8ce82172fb',
    ),
    'league/pipeline' => 
    array (
      'pretty_version' => '1.0.0',
      'version' => '1.0.0.0',
      'aliases' => 
      array (
      ),
      'reference' => 'aa14b0e3133121f8be39e9a3b6ddd011fc5bb9a8',
    ),
    'michelf/php-markdown' => 
    array (
      'pretty_version' => '1.9.0',
      'version' => '1.9.0.0',
      'aliases' => 
      array (
      ),
      'reference' => 'c83178d49e372ca967d1a8c77ae4e051b3a3c75c',
    ),
    'nikic/fast-route' => 
    array (
      'pretty_version' => 'v0.7.0',
      'version' => '0.7.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '8164b4a0d8afde4eae5f1bfc39084972ba23ad36',
    ),
    'pop-schema/basic-directives' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => '7a30c452b17293d646c6dec731ce3c665cee898b',
    ),
    'pop-schema/comment-mutations' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => '009cf8fa7ecc609eb49e26aa6a14f39ca1a4979e',
    ),
    'pop-schema/comment-mutations-wp' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => '20e6a9ef71bb5ed3095f91b9deda622e0490c96c',
    ),
    'pop-schema/commentmeta' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => '0529a3a6e5083261acb7e6a570b5021a8efbc825',
    ),
    'pop-schema/commentmeta-wp' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => '077a395dd3dcd3a3d293c85e5e8af86199c2caf1',
    ),
    'pop-schema/comments' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => 'd47c8f4ea3ac7cec745d94dd2e2b4591e4a7987c',
    ),
    'pop-schema/comments-wp' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => 'dbe58f2fc26d4a608bb9017c08de3973d71eedef',
    ),
    'pop-schema/custompost-mutations' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => '0152ab5e8c5ea895cbd508ee2a4adca366891bda',
    ),
    'pop-schema/custompost-mutations-wp' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => '55b4b11aedb67a9b44b545b8f614857683b1383d',
    ),
    'pop-schema/custompostmedia' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => 'daa29b0156dc26e98a2504b2461732167cd90953',
    ),
    'pop-schema/custompostmedia-mutations' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => 'fe79d0a66df3faa5d1adf220f9e7d32a42618107',
    ),
    'pop-schema/custompostmedia-mutations-wp' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => '5d0edf3527d1d13b490092c6a3afbd217c5a2878',
    ),
    'pop-schema/custompostmedia-wp' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => '989f230a37ca23d6f97a8b04523d039eaeb56733',
    ),
    'pop-schema/custompostmeta' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => 'ae835764b61ccdfcaa03396fbe5f8babecaed8d9',
    ),
    'pop-schema/custompostmeta-wp' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => 'f06a9fc45f117752029fdbb777f43f2eb8b886e6',
    ),
    'pop-schema/customposts' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => '74af506e024fb729e84da04efb493d8f9ea85d46',
    ),
    'pop-schema/customposts-wp' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => 'a93ce1dd7116b4a89eecfa814bfaff5812fd6f45',
    ),
    'pop-schema/generic-customposts' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => '71a6f8ff85f30312406d2cd509e8467ed9fbf667',
    ),
    'pop-schema/media' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => 'f41822f7eb443435c89316408573f275771709e4',
    ),
    'pop-schema/media-wp' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => 'd9f1146a9c20261a74e34644896348b66a2b09f0',
    ),
    'pop-schema/meta' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => 'f0fa2ae300006e594e2605f59a56930fc192927d',
    ),
    'pop-schema/metaquery' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => '4288f0057d8ada957f318861ff3d2780e157a6a2',
    ),
    'pop-schema/metaquery-wp' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => 'e9706849d3a0f54800036f0de09a5e5668afe42d',
    ),
    'pop-schema/migrate-commentmeta' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => '1c82364e00238716bf1f658a71cf58fd4ef2be0a',
    ),
    'pop-schema/migrate-commentmeta-wp' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => '0e481a6f42c36240eca27454429b217388d847ac',
    ),
    'pop-schema/migrate-comments' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => '476a7480b2540e8c9eb0b368fa78d4c514ceeb52',
    ),
    'pop-schema/migrate-comments-wp' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => 'f8b9f50e086523a38b033fef14ebc2cad13d8a02',
    ),
    'pop-schema/migrate-custompostmedia' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => '7afb8b1c163f630aa37b48ec39b6563a4d3cfbb5',
    ),
    'pop-schema/migrate-custompostmedia-wp' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => '9e00c58f97c4ae242e8da2d229bcef02d616a277',
    ),
    'pop-schema/migrate-custompostmeta' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => '30f3480c892b09506ae999a51e915aa2463bad6e',
    ),
    'pop-schema/migrate-custompostmeta-wp' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => 'f0fd1b236e99ae857be2ee1f60e79f41c22ba7ea',
    ),
    'pop-schema/migrate-customposts' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => '90bb09a82df2ceaf2d12c2a8af0a775ab09a2672',
    ),
    'pop-schema/migrate-customposts-wp' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => '590f973e807648516ee880adbc268d1ffad8bf10',
    ),
    'pop-schema/migrate-media' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => 'cf36a0ec23f1df19190e939e33d89d837cb14bde',
    ),
    'pop-schema/migrate-media-wp' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => '93676e708d454e2a1d4da55aa0e84c78d45cf126',
    ),
    'pop-schema/migrate-meta' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => '7ab12d518a7efae30f651dff85e5823c199aa1f1',
    ),
    'pop-schema/migrate-metaquery' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => '25376d1b66bd1c45e7008dacd222bf46c263cd2a',
    ),
    'pop-schema/migrate-metaquery-wp' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => '1c465ecafac478274aa3f4a788a44f4577000ba5',
    ),
    'pop-schema/migrate-pages' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => '9365f877e94d893b3565125de56e037487f1a597',
    ),
    'pop-schema/migrate-pages-wp' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => '2b102c057638bea3f6b2eea66a3ff9b8647b03e9',
    ),
    'pop-schema/migrate-post-tags' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => '07af29921d6e99bc025243610275fee6a6f67fcf',
    ),
    'pop-schema/migrate-post-tags-wp' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => 'ec0e99db16fbbd0013dc1e2817bf3193f7534901',
    ),
    'pop-schema/migrate-posts' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => 'b0613cd912cee9b8dae90c08d8f4c1c6a7ee405f',
    ),
    'pop-schema/migrate-posts-wp' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => '677111cb10c00091790c536b3460d5ef89977bae',
    ),
    'pop-schema/migrate-queriedobject' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => 'db62e82caae15dcd869001992614b1284f5081c3',
    ),
    'pop-schema/migrate-queriedobject-wp' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => '340a2c4ff05be3e11495c5e09b64a6bffeb804ef',
    ),
    'pop-schema/migrate-tags' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => '2b3fe1a1d4d904ffab890498462d69bc5b31532f',
    ),
    'pop-schema/migrate-tags-wp' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => 'efb7a060dc2cb220f5454b3cdaf61a803e56a2ae',
    ),
    'pop-schema/migrate-taxonomies' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => '892eeef39c72e7b691bc8ec2757988b8260c17a7',
    ),
    'pop-schema/migrate-taxonomies-wp' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => '2186138b7c8d103eb0caef0084b1ee526f5519d2',
    ),
    'pop-schema/migrate-taxonomymeta' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => '4a74ee9b66b918e44d9c3d55e94f6f601480f8fa',
    ),
    'pop-schema/migrate-taxonomymeta-wp' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => 'fccfaf634190ff8b527e56134cc4ed6d97656ab1',
    ),
    'pop-schema/migrate-taxonomyquery' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => 'd5d43993c7f47b8d61090b78b0187c23bec1043d',
    ),
    'pop-schema/migrate-taxonomyquery-wp' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => '169e47396098f5c0cdb5b44bdf98dd6a46f15e28',
    ),
    'pop-schema/migrate-usermeta' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => '8bbfcabdcdf21605ce3873b006dd0a9c22b91fc9',
    ),
    'pop-schema/migrate-usermeta-wp' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => '7fe072c498ac9544f3bbf3e95b90b1bc57a979d7',
    ),
    'pop-schema/migrate-users' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => '9c0ec285326956986d9ea07d6443cadb6812481f',
    ),
    'pop-schema/migrate-users-wp' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => '7952041ab1bda56281175de27a8177c6b4d46dd4',
    ),
    'pop-schema/pages' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => '015300d6269b08e73062b14793fb481e72ea4552',
    ),
    'pop-schema/pages-wp' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => '426cb6c3dcc61b1672f020799409a1e23a95c908',
    ),
    'pop-schema/post-mutations' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => '4cda31262b6b998614017ae98455d18d2dea85da',
    ),
    'pop-schema/post-tags' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => '8945eb2b74a33660220ded2dbe1615fb4305e8df',
    ),
    'pop-schema/post-tags-wp' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => '1a3c70d59218f8b5852b938d71ee0d7cc7fda44f',
    ),
    'pop-schema/posts' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => '61f1c15d84f9c52e1218314744d34be15b56e8e3',
    ),
    'pop-schema/posts-wp' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => '653b39c19594fa3aecafd94402efbd95a3a7957f',
    ),
    'pop-schema/queriedobject' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => 'd061a1e6ba081e87951107c758a711ae32c177a2',
    ),
    'pop-schema/queriedobject-wp' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => 'bdbdd60f2aebfbe2578e862e000e37cde9ad0659',
    ),
    'pop-schema/schema-commons' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => 'cd8f32c5fa65aab5fafa7c77c277eee57056b899',
    ),
    'pop-schema/tags' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => 'aba42fbfa12132fb75378d915e1c464e436c20f8',
    ),
    'pop-schema/tags-wp' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => 'ce3f5f7f3fb647294b527cfce600e5a9ad87e76e',
    ),
    'pop-schema/taxonomies' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => '9497eebe142a1ef9bfb5d9e97e3135d85ba75745',
    ),
    'pop-schema/taxonomies-wp' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => '11f35a8612d66c12bd4ad368046d1b76f75928ac',
    ),
    'pop-schema/taxonomymeta' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => '0f53f26a862cb8f0f5fccdffce577c6832dc607c',
    ),
    'pop-schema/taxonomymeta-wp' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => '7e34ee1548cf06bb4c004fe1883282dffe648fd3',
    ),
    'pop-schema/taxonomyquery' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => 'b88ad5706d1b11dc7e81a5576776d0889b2b09a6',
    ),
    'pop-schema/taxonomyquery-wp' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => '9c33bf99f69ed56e0ac1a3538da7a40d61646b93',
    ),
    'pop-schema/user-roles' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => '7f56f0bdcef2361d6b303b8b04e7c8bad892b5a0',
    ),
    'pop-schema/user-roles-access-control' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => '17d1537642b79b0f42b0610d87cd8a92f65e15cc',
    ),
    'pop-schema/user-roles-wp' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => '129caa52e57e774f7e9845f88927e46a033ddc65',
    ),
    'pop-schema/user-state' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => '8eb268c5ec0e29564ac0fed826c17659ffac33a7',
    ),
    'pop-schema/user-state-access-control' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => 'b3bfa14e95977ee2f17ee653cb9ed24b9e06dbec',
    ),
    'pop-schema/user-state-mutations' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => '7d342ec8322d0d3dffc304c03dc6fa11a10c0bbc',
    ),
    'pop-schema/user-state-mutations-wp' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => 'ce2a7a16cf98c2765d1c46c83c74a1d96b9732f5',
    ),
    'pop-schema/user-state-wp' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => 'cc00874be99693721fd415971e45381565b4f26c',
    ),
    'pop-schema/usermeta' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => '9e6f4c916a428c378d72319dc1153c40a9432af5',
    ),
    'pop-schema/usermeta-wp' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => 'f0d3228651e9b0003d46178fa89c7b53deff74bc',
    ),
    'pop-schema/users' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => 'a4b4f43a14f60110768d4d00f3f6e8d172bf9b13',
    ),
    'pop-schema/users-wp' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => '5f99e185933411fecd5386412b6cb2202287c0b2',
    ),
    'psr/cache' => 
    array (
      'pretty_version' => '1.0.1',
      'version' => '1.0.1.0',
      'aliases' => 
      array (
      ),
      'reference' => 'd11b50ad223250cf17b86e38383413f5a6764bf8',
    ),
    'psr/cache-implementation' => 
    array (
      'provided' => 
      array (
        0 => '1.0',
      ),
    ),
    'psr/container' => 
    array (
      'pretty_version' => '1.0.0',
      'version' => '1.0.0.0',
      'aliases' => 
      array (
      ),
      'reference' => 'b7ce3b176482dbbc1245ebf52b181af44c2cf55f',
    ),
    'psr/container-implementation' => 
    array (
      'provided' => 
      array (
        0 => '1.0',
      ),
    ),
    'psr/http-message' => 
    array (
      'pretty_version' => '1.0.1',
      'version' => '1.0.1.0',
      'aliases' => 
      array (
      ),
      'reference' => 'f6561bf28d520154e4b0ec72be95418abe6d9363',
    ),
    'psr/http-message-implementation' => 
    array (
      'provided' => 
      array (
        0 => '1.0',
      ),
    ),
    'psr/log' => 
    array (
      'pretty_version' => '1.1.3',
      'version' => '1.1.3.0',
      'aliases' => 
      array (
      ),
      'reference' => '0f73288fd15629204f9d42b7055f72dacbe811fc',
    ),
    'psr/simple-cache-implementation' => 
    array (
      'provided' => 
      array (
        0 => '1.0',
      ),
    ),
    'ralouphie/getallheaders' => 
    array (
      'pretty_version' => '3.0.3',
      'version' => '3.0.3.0',
      'aliases' => 
      array (
      ),
      'reference' => '120b605dfeb996808c31b6477290a714d356e822',
    ),
    'symfony/cache' => 
    array (
      'pretty_version' => 'v5.2.3',
      'version' => '5.2.3.0',
      'aliases' => 
      array (
      ),
      'reference' => 'd6aed6c1bbf6f59e521f46437475a0ff4878d388',
    ),
    'symfony/cache-contracts' => 
    array (
      'pretty_version' => 'v2.2.0',
      'version' => '2.2.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '8034ca0b61d4dd967f3698aaa1da2507b631d0cb',
    ),
    'symfony/cache-implementation' => 
    array (
      'provided' => 
      array (
        0 => '1.0',
      ),
    ),
    'symfony/config' => 
    array (
      'pretty_version' => 'v5.2.3',
      'version' => '5.2.3.0',
      'aliases' => 
      array (
      ),
      'reference' => '50e0e1314a3b2609d32b6a5a0d0fb5342494c4ab',
    ),
    'symfony/dependency-injection' => 
    array (
      'pretty_version' => 'v5.2.3',
      'version' => '5.2.3.0',
      'aliases' => 
      array (
      ),
      'reference' => '62f72187be689540385dce6c68a5d4c16f034139',
    ),
    'symfony/deprecation-contracts' => 
    array (
      'pretty_version' => 'v2.2.0',
      'version' => '2.2.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '5fa56b4074d1ae755beb55617ddafe6f5d78f665',
    ),
    'symfony/dotenv' => 
    array (
      'pretty_version' => 'v5.2.3',
      'version' => '5.2.3.0',
      'aliases' => 
      array (
      ),
      'reference' => '783f12027c6b40ab0e93d6136d9f642d1d67cd6b',
    ),
    'symfony/expression-language' => 
    array (
      'pretty_version' => 'v5.2.3',
      'version' => '5.2.3.0',
      'aliases' => 
      array (
      ),
      'reference' => '7bf30a4e29887110f8bd1882ccc82ee63c8a5133',
    ),
    'symfony/filesystem' => 
    array (
      'pretty_version' => 'v5.2.3',
      'version' => '5.2.3.0',
      'aliases' => 
      array (
      ),
      'reference' => '262d033b57c73e8b59cd6e68a45c528318b15038',
    ),
    'symfony/polyfill-ctype' => 
    array (
      'pretty_version' => 'v1.22.1',
      'version' => '1.22.1.0',
      'aliases' => 
      array (
      ),
      'reference' => 'c6c942b1ac76c82448322025e084cadc56048b4e',
    ),
    'symfony/polyfill-intl-grapheme' => 
    array (
      'pretty_version' => 'v1.22.1',
      'version' => '1.22.1.0',
      'aliases' => 
      array (
      ),
      'reference' => '5601e09b69f26c1828b13b6bb87cb07cddba3170',
    ),
    'symfony/polyfill-intl-idn' => 
    array (
      'pretty_version' => 'v1.22.1',
      'version' => '1.22.1.0',
      'aliases' => 
      array (
      ),
      'reference' => '2d63434d922daf7da8dd863e7907e67ee3031483',
    ),
    'symfony/polyfill-intl-normalizer' => 
    array (
      'pretty_version' => 'v1.22.1',
      'version' => '1.22.1.0',
      'aliases' => 
      array (
      ),
      'reference' => '43a0283138253ed1d48d352ab6d0bdb3f809f248',
    ),
    'symfony/polyfill-mbstring' => 
    array (
      'pretty_version' => 'v1.22.1',
      'version' => '1.22.1.0',
      'aliases' => 
      array (
      ),
      'reference' => '5232de97ee3b75b0360528dae24e73db49566ab1',
    ),
    'symfony/polyfill-php72' => 
    array (
      'pretty_version' => 'v1.22.1',
      'version' => '1.22.1.0',
      'aliases' => 
      array (
      ),
      'reference' => 'cc6e6f9b39fe8075b3dabfbaf5b5f645ae1340c9',
    ),
    'symfony/polyfill-php73' => 
    array (
      'pretty_version' => 'v1.22.1',
      'version' => '1.22.1.0',
      'aliases' => 
      array (
      ),
      'reference' => 'a678b42e92f86eca04b7fa4c0f6f19d097fb69e2',
    ),
    'symfony/polyfill-php74' => 
    array (
      'pretty_version' => 'v1.22.1',
      'version' => '1.22.1.0',
      'aliases' => 
      array (
      ),
      'reference' => '577e147350331efeb816897e004d85e6e765daaf',
    ),
    'symfony/polyfill-php80' => 
    array (
      'pretty_version' => 'v1.22.1',
      'version' => '1.22.1.0',
      'aliases' => 
      array (
      ),
      'reference' => 'dc3063ba22c2a1fd2f45ed856374d79114998f91',
    ),
    'symfony/property-access' => 
    array (
      'pretty_version' => 'v5.2.3',
      'version' => '5.2.3.0',
      'aliases' => 
      array (
      ),
      'reference' => '3af8ed262bd3217512a13b023981fe68f36ad5f3',
    ),
    'symfony/property-info' => 
    array (
      'pretty_version' => 'v5.2.3',
      'version' => '5.2.3.0',
      'aliases' => 
      array (
      ),
      'reference' => '4e4f368c3737b1c175d66f4fc0b99a5bcd161a77',
    ),
    'symfony/service-contracts' => 
    array (
      'pretty_version' => 'v2.2.0',
      'version' => '2.2.0.0',
      'aliases' => 
      array (
      ),
      'reference' => 'd15da7ba4957ffb8f1747218be9e1a121fd298a1',
    ),
    'symfony/service-implementation' => 
    array (
      'provided' => 
      array (
        0 => '1.0',
      ),
    ),
    'symfony/string' => 
    array (
      'pretty_version' => 'v5.2.3',
      'version' => '5.2.3.0',
      'aliases' => 
      array (
      ),
      'reference' => 'c95468897f408dd0aca2ff582074423dd0455122',
    ),
    'symfony/var-exporter' => 
    array (
      'pretty_version' => 'v5.2.3',
      'version' => '5.2.3.0',
      'aliases' => 
      array (
      ),
      'reference' => '5aed4875ab514c8cb9b6ff4772baa25fa4c10307',
    ),
    'symfony/yaml' => 
    array (
      'pretty_version' => 'v5.2.3',
      'version' => '5.2.3.0',
      'aliases' => 
      array (
      ),
      'reference' => '338cddc6d74929f6adf19ca5682ac4b8e109cdb0',
    ),
  ),
);
private static $canGetVendors;
private static $installedByVendor = array();







public static function getInstalledPackages()
{
$packages = array();
foreach (self::getInstalled() as $installed) {
$packages[] = array_keys($installed['versions']);
}


if (1 === \count($packages)) {
return $packages[0];
}

return array_keys(array_flip(\call_user_func_array('array_merge', $packages)));
}









public static function isInstalled($packageName)
{
foreach (self::getInstalled() as $installed) {
if (isset($installed['versions'][$packageName])) {
return true;
}
}

return false;
}














public static function satisfies(VersionParser $parser, $packageName, $constraint)
{
$constraint = $parser->parseConstraints($constraint);
$provided = $parser->parseConstraints(self::getVersionRanges($packageName));

return $provided->matches($constraint);
}










public static function getVersionRanges($packageName)
{
foreach (self::getInstalled() as $installed) {
if (!isset($installed['versions'][$packageName])) {
continue;
}

$ranges = array();
if (isset($installed['versions'][$packageName]['pretty_version'])) {
$ranges[] = $installed['versions'][$packageName]['pretty_version'];
}
if (array_key_exists('aliases', $installed['versions'][$packageName])) {
$ranges = array_merge($ranges, $installed['versions'][$packageName]['aliases']);
}
if (array_key_exists('replaced', $installed['versions'][$packageName])) {
$ranges = array_merge($ranges, $installed['versions'][$packageName]['replaced']);
}
if (array_key_exists('provided', $installed['versions'][$packageName])) {
$ranges = array_merge($ranges, $installed['versions'][$packageName]['provided']);
}

return implode(' || ', $ranges);
}

throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
}





public static function getVersion($packageName)
{
foreach (self::getInstalled() as $installed) {
if (!isset($installed['versions'][$packageName])) {
continue;
}

if (!isset($installed['versions'][$packageName]['version'])) {
return null;
}

return $installed['versions'][$packageName]['version'];
}

throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
}





public static function getPrettyVersion($packageName)
{
foreach (self::getInstalled() as $installed) {
if (!isset($installed['versions'][$packageName])) {
continue;
}

if (!isset($installed['versions'][$packageName]['pretty_version'])) {
return null;
}

return $installed['versions'][$packageName]['pretty_version'];
}

throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
}





public static function getReference($packageName)
{
foreach (self::getInstalled() as $installed) {
if (!isset($installed['versions'][$packageName])) {
continue;
}

if (!isset($installed['versions'][$packageName]['reference'])) {
return null;
}

return $installed['versions'][$packageName]['reference'];
}

throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
}





public static function getRootPackage()
{
$installed = self::getInstalled();

return $installed[0]['root'];
}







public static function getRawData()
{
return self::$installed;
}



















public static function reload($data)
{
self::$installed = $data;
self::$installedByVendor = array();
}




private static function getInstalled()
{
if (null === self::$canGetVendors) {
self::$canGetVendors = method_exists('Composer\Autoload\ClassLoader', 'getRegisteredLoaders');
}

$installed = array();

if (self::$canGetVendors) {

foreach (ClassLoader::getRegisteredLoaders() as $vendorDir => $loader) {
if (isset(self::$installedByVendor[$vendorDir])) {
$installed[] = self::$installedByVendor[$vendorDir];
} elseif (is_file($vendorDir.'/composer/installed.php')) {
$installed[] = self::$installedByVendor[$vendorDir] = require $vendorDir.'/composer/installed.php';
}
}
}

$installed[] = self::$installed;

return $installed;
}
}
