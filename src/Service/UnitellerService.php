<?php

namespace App\Service;

use App\Entity\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class UnitellerService
 * @package App\Service
 */
class UnitellerService
{
    const SHOP_IDP = 4996;

    const LIFE_TIME = 300;

    const SECRET_KEY = 'IzOb37ygmN9xAUdKFtLcVt82x2ir1ycGSXkTch03dblOPsLOGAyADKHC3WWVfcXNAOwLdxb2LaWa4vWH';

    private $router;

    public function __construct(UrlGeneratorInterface $router)
    {
        $this->router = $router;
    }

    /**
     * @param Request $req
     *
     * @return array
     * @throws \Symfony\Component\Routing\Exception\InvalidParameterException
     * @throws \Symfony\Component\Routing\Exception\MissingMandatoryParametersException
     * @throws \Symfony\Component\Routing\Exception\RouteNotFoundException
     */
    public function getFromData(Request $req)
    {
        $user = $req->getUser();
        $fields = [
            'Shop_IDP' => self::SHOP_IDP,
            'Order_IDP' => $req->getId(),
            'Subtotal_P' => $req->getSum(),
            'MeanType' => '',
            'EMoneyType' => '',
            'Lifetime' => self::LIFE_TIME,
            'Customer_IDP' => $user->getId(),
            'Card_IDP' => '',
            'IData' => '',
            'PT_Code' => '',
            'URL_RETURN' => $this->router->generate('donate_status'),
            'URL_RETURN_OK' => $this->router->generate('donate_ok'),
            'URL_RETURN_NO' => $this->router->generate('donate_no'),
            'Email' => $user->getEmail(),
            'CallbackFormat' => 'json',
            'LastName' => $user->getLastName(),
            'FirstName' => $user->getFirstName(),
            'MiddleName' => $user->getMiddleName(),
            'Phone' => $user->getPhone()
        ];

        if ($req->isRecurent()) {
            $fields['IsRecurrentStart'] = 1;
        }

        $fields['Signature'] = $this->getSignature($fields);

        return $fields;
    }

    /**
     * @param array $data
     *
     * @return string
     */
    private function getSignature(array $data): string
    {
        $arr = [
            $data['Shop_IDP'],
            $data['Order_IDP'],
            $data['Subtotal_P'],
            $data['MeanType'] ?? '',
            $data['EMoneyType'] ?? '',
            $data['Lifetime'] ?? '',
            $data['Customer_IDP'] ?? '',
            $data['Card_IDP'] ?? '',
            $data['IData'] ?? '',
            $data['PT_Code'] ?? ''
        ];

        if (isset($data['OrderLifetime'])) {
            $arr[] = $data['OrderLifetime'];
        }

        $arr[] = self::SECRET_KEY;

        foreach ($arr as $key => $value) {
            $arr[$key] = md5($value);
        }

        return strtoupper(md5(implode('&', $arr)));
    }
}