<?php

namespace App\DataFixtures;

use App\Entity\Child;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ChildFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @param ObjectManager $manager
     *
     * @throws \Exception
     */
    public function load(ObjectManager $manager)
    {
        $child = new Child();
        $child
            ->setName('Кузьмин Иван')
            ->setBirthdate((new \DateTime())->sub(new \DateInterval('P12Y')))
            ->setDiagnosis('Детский церебральный паралич, двойная гемиплегия.')
            ->setImages([
                'https://user-images.githubusercontent.com/40119577/54120087-e2150900-4407-11e9-9488-69186581e6fa.jpg',
                'https://user-images.githubusercontent.com/40119577/54120088-e2150900-4407-11e9-8e79-d1e3984576d2.jpg',
                'https://user-images.githubusercontent.com/40119577/54120091-e4776300-4407-11e9-8958-ca696e7a3815.jpeg'
            ])
            ->setComment(
                'Я, Кузьмина Ольга Артемьевна, мама, воспитывающая одна двух сыновей. Прошу помощи для старшего сына, возраст 12 лет, ребенок-инвалид. В возрасте 1 месяца у Ивана произошло массивное субарахноидальное кровоизлияние в мозг. Ребенок самостоятелтно не ходит, не сидит, не ползает, не говорит. Иван лежачий, полностью прикован к постели. Каждый раз сталкиваюсь с финансовыми трудностями, средств на очередную реабилитацию нет, прекращать лечение нельзя, поэтому очень прошу неравнодушных людей помочь мне.Интенсивное, а главное долгосрочное прохождение курсов реабилитаций по мнению врачей позволит улучшить здоровье Вани. Я очень надеюсь, что с вашей помощью мой сынок встанет на ножки!'
            )
            ->setGoal(906700)
            ->setCollected(0)
            ->setRequisites('Чувашское отделение №8613<br>
ПАО СБЕРБАНК г. Чебоксары<br>
Счет: 40817810775041307198<br>
БИК: 049706609<br>
К/С: 30101810300000000609')
            ->setContacts('Телефон мамы (Кузьмина Ольга Артемьевна):<br>8-960-301-99-72');
        $manager->persist($child);
        $child = new Child();
        $child
            ->setName('Михайлова Ангелина')
            ->setBirthdate((new \DateTime())->sub(new \DateInterval('P9Y')))
            ->setDiagnosis('Детский церебральный паралич. Синдром Веста.')
            ->setImages([
                'https://user-images.githubusercontent.com/40119577/54120055-d1649300-4407-11e9-82de-6401ff46666b.jpeg',
                'https://user-images.githubusercontent.com/40119577/54120056-d1649300-4407-11e9-9e4f-aa8349b6fefa.jpeg',
                'https://user-images.githubusercontent.com/40119577/54120057-d1649300-4407-11e9-9438-8adec4907f4e.jpeg'
            ])
            ->setComment(
                'Здравствуйте, очень надеюсь на помощь неравнодушных людей. Помощи мне ждать неоткуда, поэтому вынуждена обратиться за помощью в благотворительный фонд. Я прошу помощи на лечение моей дочери. Ей 9 лет. После рождения у неё были не раскрытые лёгкие и она лежала в реанимации 1,5 месяца. В 6 месяцев нам поставили диагноз синдром Веста, а также сообщили, что моя дочь будет инвалидом .Затем мы узнали, что у Ангелины атрофия зрительного нерва. Однако и это не всё, у нас началась спастика тела. Мы делаем каждый день массаж и пьём препараты от спастики , сейчас мы сидим не более двух часов. Специалисты говорят, что долгосрочные курсы реабилитаций помогут улучшить здоровье моей дочки. Спасибо Вам за помощь!'
            )
            ->setGoal(1333300)
            ->setCollected(0)
            ->setRequisites('Чувашское отделение №8613<br>
ПАО СБЕРБАНК г. Чебоксары<br>
Счет: 40817810875041301955<br>
БИК: 049706609<br>
К/С: 30101810300000000609')
            ->setContacts('Телефон мамы (Михайлова Анна Севировна):<br>8-952-024-02-70');
        $manager->persist($child);
        $child = new Child();
        $child
            ->setName('Захаров Никита')
            ->setBirthdate((new \DateTime())->sub(new \DateInterval('P7Y')))
            ->setDiagnosis('Детский церебральный паралич. Спастический тетрапарез.')
            ->setImages([
                'https://user-images.githubusercontent.com/40119577/54119698-03292a00-4407-11e9-92ba-f11a615593af.jpg',
                'https://user-images.githubusercontent.com/40119577/54119700-03c1c080-4407-11e9-9cdb-0ba667b9c96e.jpg',
                'https://user-images.githubusercontent.com/40119577/54119701-03c1c080-4407-11e9-9f95-2e2d5981111f.jpg',
                'https://user-images.githubusercontent.com/40119577/54120005-babe3c00-4407-11e9-8eca-6b9335072915.jpg'
            ])
            ->setComment(
                'Здравствуйте, я оказалась в очень тяжелой ситуации, одна с маленьким ребенком-инвалидом на руках. Я прошу помощи на лечение моего сына. Ему 7 лет , он инвалид с рождения. Мы прошли через многое: начиная от неправильных диагнозов до устранения последствий перитонита. Мой ребенок первый выживший в таком состоянии в нашем городе, ни один врач не верил в то, что он останется жить. Я постоянно вожу ребенка на реабилитацию в разные города, беру кредиты на лечение , сейчас дошла до финансового тупика, прекращать лечение нельзя, у ребенка сразу усиливается спастика в ногах , поэтому очень прошу неравнодушных людей помочь мне. Спасибо всем огромное, кто откликнется на мою мольбу о помощи.'
            )
            ->setGoal(1733000)
            ->setCollected(0)
            ->setRequisites('Чувашское отделение №8613<br>
ПАО СБЕРБАНК г. Чебоксары<br>
Счет: 40817810575170502145<br>
БИК: 049706609<br>
К/С: 30101810300000000609')
            ->setContacts('Телефон мамы (Михайлова Анна Севировна):<br>8-952-024-02-70');
        $manager->persist($child);
        $manager->flush();
    }
}
