<?php

namespace PagarMe\Sdk\Transaction;

trait TransactionBuilder
{
    use SplitRuleBuilder;

    private function buildTransaction($transactionData)
    {
        if (isset($transactionData->split_rules)) {
            $transactionData->split_rules = $this->buildSplitRules(
                $transactionData->split_rules
            );
        }

        $transactionData->date_created = new \DateTime(
            $transactionData->date_created
        );
        $transactionData->date_updated = new \DateTime(
            $transactionData->date_updated
        );

        if ($transactionData->payment_method == BoletoTransaction::PAYMENT_METHOD) {
            $transactionData->boleto_expiration_date = new \DateTime(
                $transactionData->boleto_expiration_date
            );

            return new BoletoTransaction(get_object_vars($transactionData));
        }

        if ($transactionData->payment_method == CreditCardTransaction::PAYMENT_METHOD) {
            return new CreditCardTransaction(get_object_vars($transactionData));
        }

        throw new UnsupportedTransaction(
            sprintf(
                'Transaction type: %s, is not supported',
                $transactionData->payment_method
            ),
            1
        );
    }
}