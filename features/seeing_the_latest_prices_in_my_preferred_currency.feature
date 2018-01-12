@exchange_rates_import
Feature: Seeing the latest prices in my preferred currency
    In order to have the most precise exchange rates on the prices
    As a Customer
    I want them to be updated from a reliable source

    Scenario: Seeing the product price calculated with reliable exchange rate
        Given the store operates on a single channel in "United States"
        And that channel allows to shop using the "EUR" currency
        And the store has a product "Liip T-Shirt" priced at "$9.99"
        And reliable source set the exchange rate between "USD" and "EUR" to "0.82"
        And last night the store updated exchange rates based on this source
        And I have product "Liip T-Shirt" in the cart
        When I switch to the "EUR" currency
        Then my cart's total should be "€8.19"

    Scenario: Seeing the correct prices when having more than two currencies
        Given the store operates on a single channel in "United States"
        And that channel allows to shop using "EUR" and "CHF" currencies
        And the store has a product "Liip T-Shirt" priced at "$9.99"
        And reliable source set the exchange rate between "USD" and "EUR" to "0.82"
        And reliable source set the exchange rate between "USD" and "CHF" to "0.97"
        And last night the store updated exchange rates based on this source
        And I have product "Liip T-Shirt" in the cart
        When I switch to the "CHF" currency
        Then my cart's total should be "CHF9.69"
