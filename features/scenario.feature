Feature: Scenarios

Scenario Outline: I save values as properties and use them again later
  Given I store "<value>" into "<value>"
  Then "<value>" should equal "<value>"
  
  Examples:
    | value       |
    | !           |
    | @           |
    | #           |
    | $           |
    | %           |
    | ^           |
    | &           |
    | *           |
    | (           |
    | )           |
    | -           |
    | =           |
    | ()          |
    | with spaces |
    | $sVar       |
    
Scenario: I save an array as a property
  Given "value" is:
    | key  | value  |
    | key1 | value1 |
    | key2 | value2 |
  Then "value" should be set
  Then "non-value" should not be set
  And "value" should equal:
    | key  | value  |
    | key1 | value1 |
    | key2 | value2 |
  

Scenario: I unset a property
  Given I store "Foo" into "Bar"
  When I unset "Bar"
  Then "Bar" should not be set
  
