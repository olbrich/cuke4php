@wire
Feature: Interact with the Cuke4Php server
    As a tester
    I want to run step definitions in PHP
    So that I can execute steps in PHP

Scenario: Cuke4Php runs the steps
    Given some setup
    When I take an action
    Then something happens
    And an undefined step with a "param"
    And a step with a "parameter" and the following table:
        | column 1 | data |
        | apples   | 10   |
        | oranges  | 20   |
    And a step with a multiline string:
      """
      Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus pulvinar,
      est at molestie placerat, risus lectus dictum nulla, id blandit nunc metus at
      ligula. Nam purus quam, consectetur ut pulvinar eu, elementum quis nibh. Curabitur
      pellentesque sapien ut dolor commodo ultricies. Sed auctor convallis nunc, et
      dapibus leo ullamcorper scelerisque. Vivamus nisi diam, vestibulum eget consequat
      et, fringilla vitae ipsum. Nam et nisl est. Cras ac iaculis nisl. Quisque
      condimentum tristique tellus, vitae fringilla nulla dictum vel. Proin varius congue
      libero id facilisis. Etiam scelerisque mauris sit amet sem imperdiet a laoreet tellus
      scelerisque. Curabitur vulputate congue malesuada. Aenean tempus viverra arcu, quis
      posuere velit facilisis at. Sed nec rutrum turpis. Nunc mattis magna in ante tincidunt
      fermentum. Donec dapibus porta ipsum, eget porttitor magna viverra vitae. Quisque suscipit,
      metus sit amet placerat interdum, enim turpis imperdiet turpis, in placerat felis
      orci at sem. Suspendisse sagittis, neque quis sodales ornare, dui purus posuere
      lectus, id feugiat nunc lectus ac lectus.
      """

Scenario Outline: run several steps many times
    Given I <verb> the <noun>
    Then It should be <adjective>

    Examples:
    | verb       | noun      | adjective |
    | shoot      | messenger | dead      |
    | understand | meaning   | clear     |

@error    
Scenario Outline: Error Handling
  When an error "<error_constant>" with message "<error_message>" occurs
  Then an "<exception>" should be caught
  
  Examples:
    | error_constant | error_message         | exception                       |
    | E_USER_ERROR   | an error has occurred | PHPUnit_Framework_Error         |
    | E_USER_WARNING | a warning message     | PHPUnit_Framework_Error_Warning |
    | E_USER_NOTICE  | a notice message      | PHPUnit_Framework_Error_Notice  |

@exception
Scenario: Exception Handling
  When an "Exception" is thrown with message "generic exception"
  Then an "Exception" should be caught  