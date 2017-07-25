<?php

namespace ByTIC\MFinante\Parsers;

use ByTIC\MFinante\Models\Company;
use DOMElement;

/**
 * Class CompanyPage
 * @package ByTIC\MFinante\Scrapers
 */
class CompanyPage extends AbstractParser
{

    /**
     * @return array
     */
    protected function generateContent()
    {
        $return = [];
        $return['cif'] = $this->parseCif();
        $return = array_merge($return, $this->parseTable());
        $return['balance_sheets'] = $this->parseBalanceSheetsYears();
        return $return;
    }

    /**
     * @inheritdoc
     */
    public function getModelClassName()
    {
        return Company::class;
    }

    /**
     * @return string
     */
    protected function parseCif()
    {
        $html = $this->getCrawler()->filter('#main > div[align="center"] > b')->html();
        return trim(str_replace('AGENTUL ECONOMIC CU CODUL UNIC DE IDENTIFICARE', '', $html));
    }

    /**
     * @return array
     */
    protected function parseTable()
    {
        $table = $this->getCrawler()->filter('#main > center > table > tbody')->first();
        $rows = $table->children();
        $return = [];
        foreach ($rows as $row) {
            $rowParsed = $this->parseTableRow($row);
            if ($rowParsed) {
                $return[$rowParsed[0]] = $rowParsed[1];
            }
        }
        return $return;
    }

    /**
     * @param DOMElement $row
     * @return array
     */
    protected function parseTableRow($row)
    {
        $label = $row->childNodes[0]->nodeValue;
        $label = str_replace(':', '', $label);
        $label = str_replace('(*)', '', $label);
        $label = str_replace('(**)', '', $label);
        $label = str_replace("\n", ' ', $label);
        $label = str_replace("\t", ' ', $label);
        $label = preg_replace('/\s+/', ' ', $label);
        $label = trim($label);

        $value = str_replace("\n", ' ', $row->childNodes[2]->nodeValue);
        $value = preg_replace('/\s+/', ' ', $value);
        $value = trim($value);

        $labels = self::getLabelMaps();

        $labelFind = array_search($label, $labels);
        if ($labelFind) {
            return [$labelFind, $value];
        }
        return [];
    }

    /**
     * @return array
     */
    protected function parseBalanceSheetsYears()
    {
        $select = $this->getCrawler()->filter('form[name="codfiscalForm"] > select')->first();
        $options = $select->children();
        $years = [];
        foreach ($options as $option) {
            $years[] = $option->nodeValue;
        }
        return $years;
    }

    /**
     * @return array
     */
    public static function getLabelMaps()
    {
        return [
            'name' => 'Denumire platitor',
            'address' => 'Adresa',
            'county' => 'Judetul',
            'trade_register_code' => 'Numar de inmatriculare la Registrul Comertului',
            'authorization_code' => 'Act autorizare',
            'postal_code' => 'Codul postal',
            'phone' => 'Telefon',
            'fax' => 'Fax',
            'state' => 'Stare societate',
            'observations' => 'Observatii privind societatea comerciala',
            'date_last_statement' => 'Data inregistrarii ultimei declaratii',
            'date_last_processing' => 'Data ultimei prelucrari',
            'tax_profit' => 'Impozit pe profit (data luarii in evidenta)',
            'tax_income' => 'Impozit pe veniturile microintreprinderilor (data luarii in evidenta)',
            'tax_excise' => 'Accize (data luarii in evidenta)',
            'tax_vat' => 'Taxa pe valoarea adaugata (data luarii in evidenta)',
            'contribution_social' => 'Contributia de asigurari sociale (data luarii in evidenta)',
            'contribution_insurance' => 'Contributia de asigurare pentru accidente de munca si boli profesionale '
                . 'datorate de angajator (data luarii in evidenta)',
            'contribution_unemployment' => 'Contributia de asigurari pentru somaj (data luarii in evidenta)',
            'contribution_debts_fund' => 'Contributia angajatorilor pentru Fondul de garantare pentru plata creantelor'
                . ' sociale (data luarii in evidenta)',
            'contribution_medical' => 'Contributia pentru asigurari de sanatate (data luarii in evidenta)',
            'contribution_leaves' => 'Contributii pentru concedii si indemnizatii de la persoane juridice sau fizice'
                . ' (data luarii in evidenta)',
            'tax_gambling' => 'Taxa jocuri de noroc (data luarii in evidenta)',
            'tax_salaries' => 'Impozit pe veniturile din salarii si asimilate salariilor (data luarii in evidenta)',
            'tax_buildings' => 'Impozit pe constructii(data luarii in evidenta)',
            'tax_oil_gas' => 'Impozit la titeiul si la gazele naturale din productia interna (data luarii in evidenta)',
            'tax_mining' => 'Redevente miniere/Venituri din concesiuni si inchirieri (data luarii in evidenta)',
            'tax_oil' => 'Redevente petroliere (data luarii in evidenta)',
        ];
    }
}
