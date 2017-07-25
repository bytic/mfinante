<?php

namespace ByTIC\MFinante\Parsers;

use ByTIC\MFinante\Models\BalanceSheet;
use ByTIC\MFinante\Scrapers\BalanceSheetPage as Scraper;
use DOMElement;

/**
 * Class BalanceSheetPage
 * @package ByTIC\MFinante\Scrapers
 *
 * @method Scraper getScraper()
 */
class BalanceSheetPage extends AbstractParser
{

    /**
     * @inheritdoc
     */
    public function getModelClassName()
    {
        return BalanceSheet::class;
    }

    /**
     * @return array
     */
    protected function generateContent()
    {
        $return = [];
        $return = array_merge($return, $this->parseTable());
        return $return;
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

        $labels = self::getLabelMaps($this->getScraper()->getYear());

        $labelFind = array_search($label, $labels);
        if ($labelFind) {
            return [$labelFind, $value];
        }
        return [];
    }

    /**
     * @param $year
     * @return array
     */
    public static function getLabelMaps($year)
    {
        $return = [
            'fixed_assets' => 'A. Active imobilizate - total',
            'current_assets' => 'B. Active circulante - total',
            'prepayments' => 'C. Cheltuieli in avans',
            'debts_year' => 'D. Datorii ce trebuie platite intr-o perioada de pana la un an',
            'net_current_assets' => 'E. Active circulante nete, respectiv datorii curente nete',
            'total_assets' => 'F. Total active minus datorii curente',
            'debts_year_plus' => 'G. Datorii ce trebuie platite intr-o perioada mai mare de un an',
            'provisions' => 'H. Provizioane',
            'income_advance' => 'I. Venituri in avans',
            'equity' => 'J. Capitaluri proprii - total',
            'funds_non_profit' => 'Fonduri privind activitatile fara scop patrimonial',
            'total_capital' => 'Capitaluri Total',

            'caen_non_profit' => 'CAEN privind activitatile fara scop patrimonial',
            'employees_non_profit' => 'Efectivul de personal privind activitatea fara scop patrimonial',
            'caen_economic' => 'CAEN privind activitatile economice sau financiare',
            'employees_economic' => 'Efectivul de personal privind activitatile economice sau financiare',
        ];

        $expenses = [
            'non_profit' => 'fara scop patrimonial',
            'special' => 'cu destinatie speciala',
            'economic' => 'economice',
            'total' => 'totale',
        ];
        $indicators = [
            'revenues' => 'Venituri',
            'expenses' => 'Cheltuieli',
            'surplus' => 'Excedent',
            'deficit' => 'Deficit',
        ];

        foreach ($expenses as $expenseKey => $expenseLabel) {
            if ($expenseKey == 'economic') {
                array_pop($indicators);
                array_pop($indicators);
                $indicators['profit'] = 'Profit';
                $indicators['loss'] = 'Pierdere';
            }
            foreach ($indicators as $indicatorKey => $indicatorLabel) {
                if ($expenseKey == 'total') {
                    if (in_array($indicatorKey, ['profit', 'loss'])) {
                        $indicatorLabel = ($indicatorKey == 'profit') ? 'Excedent/Profit' : 'Deficit/Pierdere';
                        $return['' . $indicatorKey . '_' . $expenseKey . '_provisions']
                            = $indicatorLabel . ' - prevederi anuale';
                        $return['' . $indicatorKey . '_' . $expenseKey . '_endyear']
                            = $indicatorLabel . ' - la 31.12.' . $year;
                    } else {
                        $return['' . $indicatorKey . '_' . $expenseKey . '_endyear']
                            = $indicatorLabel . ' ' . $expenseLabel . ' - la 31.12.' . $year;

                        $return['' . $indicatorKey . '_' . $expenseKey . '_provisions']
                            = $indicatorLabel . ' ' . $expenseLabel . ' - prevederi anuale';
                    }
                } else {
                    $linkAttribute = $indicatorKey == 'expenses' ? 'privind' : 'din';
                    $return['' . $indicatorKey . '_' . $expenseKey . '_provisions']
                        = $indicatorLabel . ' ' . $linkAttribute . ' activitatile ' . $expenseLabel . ' - prevederi anuale';

                    $return['' . $indicatorKey . '_' . $expenseKey . '_endyear']
                        = $indicatorLabel . ' ' . $linkAttribute . ' activitatile ' . $expenseLabel . ' - la 31.12.' . $year;
                }
            }
        }

        return $return;
    }
}
