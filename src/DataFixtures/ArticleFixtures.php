<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Ramsey\Uuid\Uuid;

class ArticleFixtures extends Fixture implements FixtureGroupInterface
{
    const LOCALE_LIST = ['ar_EG', 'ar_JO', 'ar_SA', 'at_AT', 'bg_BG', 'bn_BD', 'cs_CZ', 'da_DK', 'de_AT', 'de_CH', 'de_DE', 'el_CY', 'el_GR', 'en_AU', 'en_CA', 'en_GB', 'en_HK', 'en_IN', 'en_NG', 'en_NZ', 'en_PH', 'en_SG', 'en_UG', 'en_US', 'en_ZA', 'es_AR', 'es_ES', 'es_PE', 'es_VE', 'et_EE', 'fa_IR', 'fi_FI', 'fr_BE', 'fr_CA', 'fr_CH', 'fr_FR', 'he_IL', 'hr_HR', 'hu_HU', 'hy_AM', 'id_ID', 'is_IS', 'it_CH', 'it_IT', 'ja_JP', 'ka_GE', 'kk_KZ', 'ko_KR', 'lt_LT', 'lv_LV', 'me_ME', 'mn_MN', 'ms_MY', 'nb_NO', 'ne_NP', 'nl_BE', 'nl_NL', 'pl_PL', 'pt_BR', 'pt_PT', 'ro_MD', 'ro_RO', 'ru_RU', 'sk_SK', 'sl_SI', 'sr_Cyrl_RS', 'sr_Latn_RS', 'sr_RS', 'sv_SE', 'th_TH', 'tr_TR', 'uk_UA', 'vi_VN', 'zh_CN', 'zh_TW'];

    public static function getGroups(): array
    {
        return ['articles'];
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 10; $i++) {
            $locale = array_rand(self::LOCALE_LIST);
            $faker  = Factory::create(self::LOCALE_LIST[$locale]);

            $article = new Article();
            $article->setTitle($faker->sentence());
            $article->setHeadingContent($faker->realTextBetween(200, 500));
            $article->setContent($faker->realTextBetween(500, 1500));
            $article->setUid(Uuid::uuid7()->toString());

            $manager->persist($article);

            if (($i % 50) === 0) {
                $manager->flush();
                $manager->clear();
            }
        }

        $manager->flush();
        $manager->clear();
    }
}