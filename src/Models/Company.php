<?php

namespace ByTIC\MFinante\Models;

/**
 * Class Company
 * @package ByTIC\MFinante\Models
 */
class Company
{
    /**
     * CODUL UNIC DE IDENTIFICARE
     * @var string
     */
    protected $cif;

    /**
     * Denumire platitor
     * @var string
     */
    protected $name;

    /**
     * Adresa
     * @var string
     */
    protected $address;

    /**
     * Judetul
     * @var string
     */
    protected $county;

    /**
     * Numar de inmatriculare la Registrul Comertului
     * @var string
     */
    protected $trade_register_code;

    /**
     * Act autorizare
     * @var string
     */
    protected $authorization_code;

    /**
     * Codul postal
     * @var string
     */
    protected $postal_code;

    /**
     * Telefon
     * @var string
     */
    protected $phone;

    /**
     * Fax
     * @var string
     */
    protected $fax;

    /**
     * Stare societate
     * @var string
     */
    protected $state;

    /**
     * Observatii privind societatea comerciala
     * @var string
     */
    protected $observations;

    /**
     * Data inregistrarii ultimei declaratii
     * @var string
     */
    protected $date_last_statement;

    /**
     * Data ultimei prelucrari
     * @var string
     */
    protected $date_last_processing;

    /**
     * Impozit pe profit (data luarii in evidenta)
     * @var string
     */
    protected $tax_profit;

    /**
     * Impozit pe veniturile microintreprinderilor (data luarii in evidenta)
     * @var string
     */
    protected $tax_income;

    /**
     * Accize (data luarii in evidenta)
     * @var string
     */
    protected $tax_excise;

    /**
     * Taxa pe valoarea adaugata (data luarii in evidenta)
     * @var string
     */
    protected $tax_vat;

    /**
     * Contributia de asigurari sociale (data luarii in evidenta)
     * @var string
     */
    protected $contribution_social;

    /**
     * Contributia de asigurare pentru accidente de munca si boli profesionale
     * datorate de angajator (data luarii in evidenta)
     * @var string
     */
    protected $contribution_insurance;

    /**
     * Contributia de asigurari pentru somaj (data luarii in evidenta)
     * @var string
     */
    protected $contribution_unemployment;

    /**
     * Contributia angajatorilor pentru Fondul de garantare pentru plata creantelor sociale (data luarii in evidenta)
     * @var string
     */
    protected $contribution_debts_fund;

    /**
     * Contributia pentru asigurari de sanatate (data luarii in evidenta):
     * @var string
     */
    protected $contribution_medical;

    /**
     * Contributii pentru concedii si indemnizatii de la persoane juridice sau fizice (data luarii in evidenta)
     * @var string
     */
    protected $contribution_leaves;

    /**
     * Taxa jocuri de noroc (data luarii in evidenta)
     * @var string
     */
    protected $tax_gambling;

    /**
     * Impozit pe veniturile din salarii si asimilate salariilor (data luarii in evidenta)
     * @var string
     */
    protected $tax_salaries;

    /**
     * Impozit pe constructii(data luarii in evidenta)
     * @var string
     */
    protected $tax_buildings;

    /**
     * Impozit la titeiul si la gazele naturale din productia interna (data luarii in evidenta)
     * @var string
     */
    protected $tax_oil_gas;

    /**
     * Redevente miniere/Venituri din concesiuni si inchirieri (data luarii in evidenta)
     * @var string
     */
    protected $tax_mining;

    /**
     * Redevente petroliere (data luarii in evidenta)
     * @var string
     */
    protected $tax_oil;

    /**
     * Array of balance sheets available years
     * @var array
     */
    protected $balance_sheets = [];
}
