# Financial Projection Tool

## Purpose

Provide it with financial inputs and see how our financial future will play out.


## Inputs

There are six types of inputs: Account, Income, Mortgage, Debt, Fixed Expense, Variable Expense.

There is one other type of input that is still in consideration and might not
continue to exist: Event.

### Account

Pretty much just a balance and open date.

Dream code:

```php
$projection->addAccount([
  "openDate" => "2014-01-01",
  "balance" => 1000.00,
));
```

Questions:

+ What about interest?
+ What about showing balances for individual accounts in projection info?

### Income

Recurring increase to an account balance. Can recur on specific dates of the month
or after certain length of time starting from a certain date. Linked to an account.

Dream code:

```php
$projection->addIncome([
  "startDate" => "2014-01-01",
  "salary" => 50000.00,
  "schedule" => [
    "type" => "bimonthly",
    "dates" => [ 8, 23 ], // TODO understand how this is going to work
  ]
]);
```

Questions:

+ How does the payment schedule work?
+ How much is paid each pay day?
+ What about deducations?
+ What about tithing?
+ What about deposit fixed amount to one account and percent to two others?
+ What about hourly income?
+ What about every two weeks?

### Mortgage

Recurring payment. Taxes. Insurance. PMI. Interest rate. Balance. Length of time (15, 30).
Start date. Extra payments. Linked to an account.

Dream code:

```php
$projection->addMortgage([
  TODO
]);
```

Questions:

+ TODO

### Debt

Recurring payment. Start date. Payment amount. Interest rate. Balance. Extra payments.
Linked to an account.

Dream code:

```php
TODO
```

Questions:

+ TODO

### Fixed Expense

Fixed expenses. They are the same every month. Occur on a specific date every month.

Dream code:

```php
TODO
```

Questions:

+ TODO

### Variable Expense.

Variable expenses. They change every month. Occur on a specific date every month.

Dream code:

```php
TODO
```

Questions:

+ TODO

### Events

Events change something at a certain point in time. That change could be anything.

Examples:

+ Add or remove a bill, mortgage, income, accounts, debt.
+ Credit or debit an account.
+ Modify a recurring event or account.

This might be accomplished by setting start and end dates on inputs. This would
eliminate events altogether.

Dream code:

```php
TODO
```

Questions:

+ TODO

## Output

For specified period of time, and level of granularity, output summary.

Dream code for obtaining projection output:

```php
$projection->getDay($year, $month, $day); // returns projection info for a single day
$projection->getMonth($year, $month); // returns projection info for a month
$projection->getYear($year); // returns projection info for a year
```

Output includes the following:

+ Summary:
  + Incomes
  + Fixed Expenses
  + Variable Expenses
  + Debt Payments
  + Mortgage Payments
  + Account Balances
