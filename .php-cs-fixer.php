<?php
/*
 * This document has been generated with
 * https://mlocati.github.io/php-cs-fixer-configurator/#version:3.17.0|configurator
 * you can change this configuration by importing this file.
 */
$config = new PhpCsFixer\Config();
return $config
    ->setRiskyAllowed(true)
    ->setRules([
        // Converts simple usages of `array_push($x, $y);` to `$x[] = $y;`.
        'array_push' => true,
        // PHP arrays should be declared using the configured syntax.
        'array_syntax' => true,
        // Use the null coalescing assignment operator `??=` where possible.
        'assign_null_coalescing_to_coalesce_equal' => true,
        // Converts backtick operators to `shell_exec` calls.
        'backtick_to_shell_exec' => true,
        // There MUST be one blank line after the namespace declaration.
        'blank_line_after_namespace' => true,
        // Ensure there is no code on the same line as the PHP open tag and it is followed by a blank line.
        'blank_line_after_opening_tag' => true,
        // Putting blank lines between `use` statement groups.
        'blank_line_between_import_groups' => true,
        // A single space or none should be between cast and variable.
        'cast_spaces' => true,
        // Class, trait and interface elements must be separated with one or none blank line.
        'class_attributes_separation' => true,
        // Whitespace around the keywords of a class, trait, enum or interfaces definition should be one space.
        'class_definition' => true,
        // When referencing an internal class it must be written using the correct casing.
        'class_reference_name_casing' => true,
        // Namespace must not contain spacing, comments or PHPDoc.
        'clean_namespace' => true,
        // Comments with annotation should be docblock when used on structural elements.
        'comment_to_phpdoc' => true,
        // Remove extra spaces in a nullable typehint.
        'compact_nullable_typehint' => true,
        // The PHP constants `true`, `false`, and `null` MUST be written using the correct casing.
        'constant_case' => true,
        // The body of each control structure MUST be enclosed within braces.
        'control_structure_braces' => true,
        // Control structure continuation keyword must be on the configured line.
        'control_structure_continuation_position' => true,
        // Curly braces must be placed as configured.
        'curly_braces_position' => true,
        // Class `DateTimeImmutable` should be used instead of `DateTime`.
        'date_time_immutable' => true,
        // Equal sign in declare statement should be surrounded by spaces or not following configuration.
        'declare_equal_normalize' => true,
        // There must not be spaces around `declare` statement parentheses.
        'declare_parentheses' => true,
        // Force strict types declaration in all files. Requires PHP >= 7.0.
        'declare_strict_types' => true,
        // Replaces `dirname(__FILE__)` expression with equivalent `__DIR__` constant.
        'dir_constant' => true,
        // Replaces short-echo `<?=` with long format `<?php echo`/`<?php print` syntax, or vice-versa.
        'echo_tag_syntax' => true,
        // Empty loop-body must be in configured style.
        'empty_loop_body' => true,
        // Empty loop-condition must be in configured style.
        'empty_loop_condition' => true,
        // PHP code MUST use only UTF-8 without BOM (remove BOM).
        'encoding' => true,
        // Replace deprecated `ereg` regular expression functions with `preg`.
        'ereg_to_preg' => true,
        // Internal classes should be `final`.
        'final_internal_class' => true,
        // PHP code must use the long `<?php` tags or short-echo `<?=` tags and not other tag variations.
        'full_opening_tag' => true,
        // Transforms imported FQCN parameters and return types in function arguments to short version.
        'fully_qualified_strict_types' => true,
        // Spaces should be properly placed in a function declaration.
        'function_declaration' => true,
        // Replace core functions calls returning constants with the constants.
        'function_to_constant' => true,
        // Ensure single space between function's argument and its typehint.
        'function_typehint_space' => true,
        // Replace `get_class` calls on object variables with class keyword syntax.
        'get_class_to_class_keyword' => true,
        // Imports or fully qualifies global classes/functions/constants.
        'global_namespace_import' => ['import_classes' => true,'import_constants' => null,'import_functions' => null],
        // There MUST be group use for the same namespaces.
        'group_import' => true,
        // Function `implode` must be called with 2 arguments in the documented order.
        'implode_call' => true,
        // Pre- or post-increment and decrement operators should be used if possible.
        'increment_style' => ['style' => 'pre'],
        // Code MUST use configured indentation type.
        'indentation_type' => true,
        // Integer literals must be in correct case.
        'integer_literal_case' => true,
        // Replaces `is_null($var)` expression with `null === $var`.
        'is_null' => true,
        // Lambda must not import variables it doesn't use.
        'lambda_not_used_import' => true,
        // All PHP files must use same line ending.
        'line_ending' => true,
        // Use `&&` and `||` logical operators instead of `and` and `or`.
        'logical_operators' => true,
        // Cast should be written in lower case.
        'lowercase_cast' => true,
        // PHP keywords MUST be in lower case.
        'lowercase_keywords' => true,
        // Class static references `self`, `static` and `parent` MUST be in lower case.
        'lowercase_static_reference' => true,
        // Replace non multibyte-safe functions with corresponding mb function.
        'mb_str_functions' => true,
        // In method arguments and method call, there MUST NOT be a space before each comma and there MUST be one space after each comma. Argument lists MAY be split across multiple lines, where each subsequent line is indented once. When doing so, the first item in the list MUST be on the next line, and there MUST be only one argument per line.
        'method_argument_space' => ['on_multiline' => 'ensure_fully_multiline'],
        // Replace `strpos()` calls with `str_starts_with()` or `str_contains()` if possible.
        'modernize_strpos' => true,
        // Replaces `intval`, `floatval`, `doubleval`, `strval` and `boolval` function calls with according type casting operator.
        'modernize_types_casting' => true,
        // Add leading `\` before constant invocation of internal constant to speed up resolving. Constant name match is case-sensitive, except for `null`, `false` and `true`.
        'native_constant_invocation' => true,
        // Function defined by PHP should be called using the correct casing.
        'native_function_casing' => true,
        // Add leading `\` before function invocation to speed up resolving.
        'native_function_invocation' => true,
        // Native type hints for functions should use the correct case.
        'native_function_type_declaration_casing' => true,
        // All instances created with `new` keyword must (not) be followed by braces.
        'new_with_braces' => true,
        // Master functions shall be used instead of aliases.
        'no_alias_functions' => true,
        // There should be no empty lines after class opening brace.
        'no_blank_lines_after_class_opening' => true,
        // There must be a comment when fall-through is intentional in a non-empty case body.
        'no_break_comment' => true,
        // The closing `? >` tag MUST be omitted from files containing only PHP.
        'no_closing_tag' => true,
        // There should not be empty PHPDoc blocks.
        'no_empty_phpdoc' => true,
        // Remove useless (semicolon) statements.
        'no_empty_statement' => true,
        // Replace accidental usage of homoglyphs (non ascii characters) in names.
        'no_homoglyph_names' => true,
        // Remove leading slashes in `use` clauses.
        'no_leading_import_slash' => true,
        // Either language construct `print` or `echo` should be used.
        'no_mixed_echo_print' => true,
        // There must not be more than one statement per line.
        'no_multiple_statements_per_line' => true,
        // Convert PHP4-style constructors to `__construct`.
        'no_php4_constructor' => true,
        // There must be no space around double colons (also called Scope Resolution Operator or Paamayim Nekudotayim).
        'no_space_around_double_colon' => true,
        // When making a method or function call, there MUST NOT be a space between the method or function name and the opening parenthesis.
        'no_spaces_after_function_name' => true,
        // There MUST NOT be a space after the opening parenthesis. There MUST NOT be a space before the closing parenthesis.
        'no_spaces_inside_parenthesis' => true,
        // Removes `@param`, `@return` and `@var` tags that don't provide any useful information.
        'no_superfluous_phpdoc_tags' => true,
        // Remove trailing whitespace at the end of non-blank lines.
        'no_trailing_whitespace' => true,
        // There MUST be no trailing spaces inside comment or PHPDoc.
        'no_trailing_whitespace_in_comment' => true,
        // There must be no trailing whitespace in strings.
        'no_trailing_whitespace_in_string' => true,
        // Imports should not be aliased as the same name.
        'no_unneeded_import_alias' => true,
        // In function arguments there must not be arguments with default values before non-default ones.
        'no_unreachable_default_argument_value' => true,
        // Unused `use` statements must be removed.
        'no_unused_imports' => true,
        // There should not be useless `null-safe-operators` `?->` used.
        'no_useless_nullsafe_operator' => true,
        // There should not be an empty `return` statement at the end of a function.
        'no_useless_return' => true,
        // There must be no `sprintf` calls with only the first argument.
        'no_useless_sprintf' => true,
        // Remove trailing whitespace at the end of blank lines.
        'no_whitespace_in_blank_line' => true,
        // There should not be space before or after object operators `->` and `?->`.
        'object_operator_without_whitespace' => true,
        // Literal octal must be in `0o` notation.
        'octal_notation' => true,
        // Orders the elements of classes/interfaces/traits/enums.
        'ordered_class_elements' => ['order' => ['use_trait']],
        // Ordering `use` statements.
        'ordered_imports' => ['imports_order' => ['class','function','const'],'sort_algorithm' => 'none'],
        // Orders the interfaces in an `implements` or `interface extends` clause.
        'ordered_interfaces' => true,
        // Sort union types and intersection types using configured order.
        'ordered_types' => ['null_adjustment' => 'always_last'],
        // PHPUnit assertion method calls like `->assertSame(true, $foo)` should be written with dedicated method like `->assertTrue($foo)`.
        'php_unit_construct' => true,
        // PHPUnit assertions like `assertInternalType`, `assertFileExists`, should be used over `assertTrue`.
        'php_unit_dedicate_assert' => true,
        // All PHPUnit test classes should be marked as internal.
        'php_unit_internal_class' => ['types' => ['final','normal']],
        // Usage of PHPUnit's mock e.g. `->will($this->returnValue(..))` must be replaced by its shorter equivalent such as `->willReturn(...)`.
        'php_unit_mock_short_will_return' => true,
        // PHPUnit classes MUST be used in namespaced version, e.g. `\PHPUnit\Framework\TestCase` instead of `\PHPUnit_Framework_TestCase`.
        'php_unit_namespaced' => true,
        // Usages of `@expectedException*` annotations MUST be replaced by `->setExpectedException*` methods.
        'php_unit_no_expectation_annotation' => true,
        // PHPUnit methods like `assertSame` should be used instead of `assertEquals`.
        'php_unit_strict' => true,
        // Adds or removes @test annotations from tests, following configuration.
        'php_unit_test_annotation' => ['style' => 'annotation'],
        // Adds a default `@coversNothing` annotation to PHPUnit test classes that have no `@covers*` annotation.
        'php_unit_test_class_requires_covers' => true,
        // The type of `@return` annotations of methods returning a reference to itself must the configured one.
        'phpdoc_return_self_reference' => true,
        // Scalar types should always be written in the same form. `int` not `integer`, `bool` not `boolean`, `float` not `real` or `double`.
        'phpdoc_scalar' => true,
        // Annotations in PHPDoc should be grouped together so that annotations of the same type immediately follow each other. Annotations of a different type are separated by a single blank line.
        'phpdoc_separation' => true,
        // Single line `@var` PHPDoc should have proper spacing.
        'phpdoc_single_line_var_spacing' => true,
        // Converts `pow` to the `**` operator.
        'pow_to_exponentiation' => true,
        // Classes must be in a path that matches their namespace, be at least one namespace deep and the class name should match the file name.
        'psr_autoloading' => true,
        // Replaces `rand`, `srand`, `getrandmax` functions calls with their `mt_*` analogs or `random_int`.
        'random_api_migration' => true,
        // Adjust spacing around colon in return type declarations and backed enum types.
        'return_type_declaration' => true,
        // Instructions must be terminated with a semicolon.
        'semicolon_after_instruction' => true,
        // Cast shall be used, not `settype`.
        'set_type_to_cast' => true,
        // Cast `(boolean)` and `(integer)` should be written as `(bool)` and `(int)`, `(double)` and `(real)` as `(float)`, `(binary)` as `(string)`.
        'short_scalar_cast' => true,
        // Converts explicit variables in double-quoted strings and heredoc syntax from simple to complex format (`${` to `{$`).
        'simple_to_complex_string_variable' => true,
        // Simplify `if` control structures that return the boolean result of their condition.
        'simplified_if_return' => true,
        // A return statement wishing to return `void` should not return `null`.
        'simplified_null_return' => true,
        // A PHP file without end tag must always end with a single empty line feed.
        'single_blank_line_at_eof' => true,
        // There should be exactly one blank line before a namespace declaration.
        'single_blank_line_before_namespace' => true,
        // There MUST NOT be more than one property or constant declared per statement.
        'single_class_element_per_statement' => ['elements' => ['property']],
        // Each namespace use MUST go on its own line and there MUST be one blank line after the use statements block.
        'single_line_after_imports' => true,
        // Convert double quotes to single quotes for simple strings.
        'single_quote' => true,
        // Each trait `use` must be done as single statement.
        'single_trait_insert_per_statement' => true,
        // Fix whitespace after a semicolon.
        'space_after_semicolon' => true,
        // Increment and decrement operators should be used if possible.
        'standardize_increment' => true,
        // Replace all `<>` with `!=`.
        'standardize_not_equals' => true,
        // Each statement must be indented.
        'statement_indentation' => true,
        // Lambdas not (indirect) referencing `$this` must be declared `static`.
        'static_lambda' => true,
        // Comparisons should be strict.
        'strict_comparison' => true,
        // Functions should be used with `$strict` param set to `true`.
        'strict_param' => true,
        // String tests for empty must be done against `''`, not with `strlen`.
        'string_length_to_empty' => true,
        // A case should be followed by a colon and not a semicolon.
        'switch_case_semicolon_to_colon' => true,
        // Removes extra spaces between colon and case value.
        'switch_case_space' => true,
        // Standardize spaces around ternary operator.
        'ternary_operator_spaces' => true,
        // Use `null` coalescing operator `??` where possible. Requires PHP >= 7.0.
        'ternary_to_null_coalescing' => true,
        // Anonymous functions with one-liner return statement must use arrow functions.
        'use_arrow_functions' => true,
        // Visibility MUST be declared on all properties and methods; `abstract` and `final` MUST be declared before the visibility; `static` MUST be declared after the visibility.
        'visibility_required' => true,
        // Add `void` return type to functions with missing or empty return statements, but priority is given to `@return` annotations. Requires PHP >= 7.1.
        'void_return' => true,
    ])
    ->setFinder(PhpCsFixer\Finder::create()
        ->in(__DIR__)
        ->exclude([
            "vendor",
            "node_modules",
            "stubs",
            "storage",
            "public"
        ])
    )
    ;
