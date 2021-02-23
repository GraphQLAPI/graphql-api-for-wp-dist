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
      'reference' => 'f12bf3be8478b4858bbfd7235013abcc3c85282e',
    ),
    'getpop/api' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => '3b34ca6b669ef0c26205d4f494a1e1902d28c409',
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
      'reference' => 'e3ca6034bce5be7f062b197f192282e46a505d12',
    ),
    'getpop/api-mirrorquery' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => '46ab73804a87647b89d31a4a30d0a49c4c88dffe',
    ),
    'getpop/cache-control' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => 'b30e6a9b387723ffe5b5300f537bb377d6f2a543',
    ),
    'getpop/component-model' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => '23de5b188bb8ef31a005df8a07bfd24397d6a7ec',
    ),
    'getpop/definitions' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => 'c8641aec35e85902edaf86ea747d60617dee1593',
    ),
    'getpop/engine' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => 'c1b1f5f6c6ee896841dc1ca3b99921debf552a0e',
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
      'reference' => '50bfeb6d9bba6a3418c9d87c8a925535c7e17c6d',
    ),
    'getpop/guzzle-helpers' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => '9c92959299fead8725325818ef419da6c7d289e6',
    ),
    'getpop/hooks' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => 'ae3eb42fbf625b32c61160ea1286c4ca2c26cee7',
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
      'reference' => 'fe9eff862ebdbf63c9a9f51d1c13bf2ff31a7a61',
    ),
    'getpop/mandatory-directives-by-configuration' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => '91a9f840abfd0f0c5813cd704850d70796d17963',
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
      'reference' => 'b02484cfd81f0641efa296b089a928f89e6498fe',
    ),
    'getpop/query-parsing' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => 'a5a012ed4ff0e63efa5330d40c1b486f73c58e81',
    ),
    'getpop/root' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => '7b7e0df739a3d75408aa88de2a300c7440a13d26',
    ),
    'getpop/routing' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => '39266a13cba061114d20a5a5a1ff98e21c34e00d',
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
      'reference' => 'be54d70759f5399da0b4e5bb651050de2318340e',
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
      'reference' => 'eec8ef0f08d83ded2df5aee63f7b07c4fc2d437f',
    ),
    'graphql-by-pop/graphql-clients-for-wp' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => 'd1041e9d4408c6a67c38d198fd844b7f9c414fb0',
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
      'reference' => '666d777c1a95def79534e4a57eb49e878897d4ea',
    ),
    'graphql-by-pop/graphql-request' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => 'e3a6ecb283810af33a76988664c2836ca71a4c66',
    ),
    'graphql-by-pop/graphql-server' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => '2addf876195c202a1d6d6f3418efd63173ad77aa',
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
      'reference' => '757f959a2db00381308ba31ae54476710226aed5',
    ),
    'pop-schema/comment-mutations' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => 'fde2f6dc221290d418fc5f1e5714a66335acaf7b',
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
      'reference' => '792ab5e7aef37caa1a2e6c54910afd25d3bacedc',
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
      'reference' => 'fc292cde618673d6e25085320a23ba1756aad3fe',
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
      'reference' => 'f89b0aa2645fd474528b1ca4e455d2f6adbc213c',
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
      'reference' => 'bb4cb179c34f4fcc5910d60529d8b187bc3473fb',
    ),
    'pop-schema/custompostmedia-mutations' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => 'af1c4ca8d4861e5eecc1f1fdd793b7241e34db53',
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
      'reference' => 'ec08f216b06091243eac7670aa48b179c354f73a',
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
      'reference' => 'b4389a0541abb0d68cc21189625cbf69a5dabb11',
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
      'reference' => '59832626bcf0eb6f1485b6ca19142afaf0cd71c7',
    ),
    'pop-schema/media' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => 'e4cc384d649586e1d917cf8a14ec1c1ac1a7dc09',
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
      'reference' => '11f84c7ea3d45984e612a3f641be619f2738bd36',
    ),
    'pop-schema/metaquery' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => '7fc26a788aa609e74db701e63a9c6e938d7fdd32',
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
      'reference' => 'eab2aea4cb69217d2168d5157234feed22c44530',
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
      'reference' => '5a399850655a153439f7d7828bfc600ea7b4e20b',
    ),
    'pop-schema/post-tags' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => 'f8d27426dff0c7a458375559db3e4fbe66d49c2e',
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
      'reference' => '1a1e28e4622bece7d8d8656dfcd90f8f0884b8fc',
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
      'reference' => '2121a0e052318f4a82b96efaf5ed1a5b0cc30c09',
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
      'reference' => '59eaa775ea26ce4a6b31ca24a5ee492ec22949c7',
    ),
    'pop-schema/tags' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => 'af0adf0cf351067d840df95bce0efe36681f1966',
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
      'reference' => '17cfbf30b4479b3baaa47dcf976cba2f7e5b484d',
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
      'reference' => '7ba585ddfcb3526be8cffc400bef42c6b3e0fe65',
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
      'reference' => '1ca898b355fe778774bb5f0f207526e67e89c320',
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
      'reference' => 'b265054a177cf984322c4d95405509a67e8d41c4',
    ),
    'pop-schema/user-roles-access-control' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => 'a024954dab91f2e8d5e3967848346010447c835f',
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
      'reference' => '1fdf9b59e1d35416eb06dc8d08573e3c211e5ed7',
    ),
    'pop-schema/user-state-access-control' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => '988e427824a529b7e002bc04e9cb6bca2ffee697',
    ),
    'pop-schema/user-state-mutations' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
        0 => '0.8.x-dev',
      ),
      'reference' => '48f7bb713982d964122d6d2d45639c3b222f9540',
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
      'reference' => '95978673d6fa45ddc1b29ec26f31f8c3a3f1adfe',
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
      'reference' => 'c8842355d1f0b4648e3866f2707e22d7fad6ac6e',
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
