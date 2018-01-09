<?php

namespace ByTIC\MFinante\Parsers\BalanceSheet;

/**
 * Class LabelMaps
 * @package ByTIC\MFinante\Parsers
 */
class LabelMaps
{
    /**
     * @var int
     */
    protected $year;

    /**
     * @var array
     */
    protected $labels = null;

    /**
     * LabelMaps constructor.
     *
     * @param $year
     */
    public function __construct($year)
    {
        $this->setYear($year);
    }

    /**
     * @return array
     */
    public function getLabels(): array
    {
        if ($this->labels === null) {
            $this->generateLabels();
        }

        return $this->labels;
    }

    protected function generateLabels()
    {
        $labels = $this->generateSimpleLabels();
        $labels = $labels + $this->generateSimpleLabels();

        $this->labels = $labels;
    }

    /**
     * @return array
     */
    protected function generateSimpleLabels()
    {
        return [
            'A. Active imobilizate - total' => 'fixed_assets',
            'ACTIVE IMOBILIZATE - TOTAL'    => 'fixed_assets',

            'B. Active circulante - total'        => 'current_assets',
            'ACTIVE CIRCULANTE - TOTAL, din care' => 'current_assets',

            'Stocuri (materii prine, materiale consumabile, productie in curs '
            . 'de executie, semifabricate, produse finite, marfuri etc.)' => 'inventory',

            'Stocuri (materii prime, materiale, productie in curs '
            . 'de executie, semifabricate, produse finite, marfuri etc.)' => 'inventory',

            'Creante'                  => 'receivables',
            'Casa si conturi la banci' => 'cash_equivalents',

            'C. Cheltuieli in avans' => 'prepayments',
            'CHELTUIELI IN AVANS'    => 'prepayments',

            'D. Datorii ce trebuie platite intr-o perioada de pana la un an' => 'debts_year',
            'DATORII'                                                        => 'liabilities',

            'E. Active circulante nete, respectiv datorii curente nete'       => 'net_current_assets',
            'F. Total active minus datorii curente'                           => 'total_assets',
            'G. Datorii ce trebuie platite intr-o perioada mai mare de un an' => 'debts_year_plus',
            'H. Provizioane'                                                  => 'provisions',
            'PROVIZIOANE'                                                     => 'provisions',

            'I. Venituri in avans' => 'income_advance',
            'VENITURI IN AVANS'    => 'income_advance',

            'J. Capitaluri proprii - total' => 'equity',

            'CAPITALURI - TOTAL, din care:' => 'equity',
            'Capital subscris varsat'       => 'subscribed_capital',
            'Patrimoniul regiei'            => 'patrimony',
            'Patrimoniul public'            => 'public_patrimony',

            'Cifra de afaceri neta' => 'net_turnover',
            'VENITURI TOTALE'       => 'revenues_total',
            'CHELTUIELI TOTALE'     => 'expenses_total',

            'Fonduri privind activitatile fara scop patrimonial' => 'funds_non_profit',
            'Capitaluri Total'                                   => 'total_capital',

            'Numar mediu de salariati'                       => 'employees',
            'Tipul de activitate, conform clasificarii CAEN' => 'caen',

            'CAEN privind activitatile fara scop patrimonial'                     => 'caen_non_profit',
            'Efectivul de personal privind activitatea fara scop patrimonial'     => 'employees_non_profit',
            'CAEN privind activitatile economice sau financiare'                  => 'caen_economic',
            'Efectivul de personal privind activitatile economice sau financiare' => 'employees_economic',
        ];
    }

    /**
     * @return int
     */
    public function getYear(): int
    {
        return $this->year;
    }

    /**
     * @param int $year
     */
    public function setYear(int $year)
    {
        $this->year = $year;
    }

    /**
     * @return array
     */
    protected function generateExpensesLabels()
    {
        $expenses   = [
            'non_profit' => 'fara scop patrimonial',
            'special'    => 'cu destinatie speciala',
            'economic'   => 'economice',
            'total'      => 'totale',
        ];
        $indicators = [
            'revenues' => 'Venituri',
            'expenses' => 'Cheltuieli',
            'surplus'  => 'Excedent',
            'deficit'  => 'Deficit',
        ];

        foreach ($expenses as $expenseKey => $expenseLabel) {
            if ($expenseKey == 'economic') {
                array_pop($indicators);
                array_pop($indicators);
                $indicators['profit'] = 'Profit';
                $indicators['loss']   = 'Pierdere';
            }
            foreach ($indicators as $indicatorKey => $indicatorLabel) {
                if ($expenseKey == 'total') {
                    if (in_array($indicatorKey, ['profit', 'loss'])) {
                        $indicatorLabel = ($indicatorKey == 'profit') ? 'Excedent/Profit' : 'Deficit/Pierdere';
                        $return[$indicatorLabel . ' - prevederi anuale']
                                        = '' . $indicatorKey . '_' . $expenseKey . '_provisions';
                        $return[$indicatorLabel . ' - la 31.12.' . $year]
                                        = '' . $indicatorKey . '_' . $expenseKey . '_endyear';
                    } else {
                        $return[$indicatorLabel . ' ' . $expenseLabel . ' - la 31.12.' . $year]
                            = '' . $indicatorKey . '_' . $expenseKey . '_endyear';

                        $return[$indicatorLabel . ' ' . $expenseLabel . ' - prevederi anuale']
                            = '' . $indicatorKey . '_' . $expenseKey . '_provisions';
                    }
                } else {
                    $linkAttribute = $indicatorKey == 'expenses' ? 'privind' : 'din';
                    $return[$indicatorLabel . ' ' . $linkAttribute . ' activitatile ' . $expenseLabel . ' - prevederi anuale']
                                   = '' . $indicatorKey . '_' . $expenseKey . '_provisions';

                    $return[$indicatorLabel . ' ' . $linkAttribute . ' activitatile ' . $expenseLabel . ' - la 31.12.' . $year]
                        = '' . $indicatorKey . '_' . $expenseKey . '_endyear';
                }
            }
        }

        return $return;
    }
}
