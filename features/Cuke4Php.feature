@wire
Feature: Interact with the Cuke4Php server
    As a tester
    I want to run step definitions in PHP
    So that I can execute steps in PHP

Scenario: Cuke4Php run's the steps
    Given some setup
    When I take an action
    Then something happens
    And an undefined step with a "param"
    And a step with a "parameter" and the following table:
        | column 1 | data |
        | apples   | 10   |
        | oranges  | 20   |

Scenario Outline: run several steps many times
    Given I <verb> the <noun>
    Then It should be <adjective>

    Examples:
    | verb       | noun      | adjective |
    | shoot      | messenger | dead      |
    | understand | meaning   | clear     |