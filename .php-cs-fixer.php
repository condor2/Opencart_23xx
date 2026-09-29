<?php

/*
 * PHP-CS-Fixer configuration for OpenCart 2.3
 * Minimum PHP version: 8.1
 *
 * Keep formatting changes safe.
 * Avoid automatic changes to runtime behaviour and method signatures.
 */

$config = new PhpCsFixer\Config();

return $config
	->setRiskyAllowed(false)
	->setIndent("\t")
	->setRules([
		'@PER-CS2x0' => true,
		'@PHP8x1Migration' => true,

		/*
		 * OpenCart 2.3 style
		 */
		'array_syntax' => false,
		'octal_notation' => false,

		'align_multiline_comment' => true,
		'array_indentation' => true,

		'binary_operator_spaces' => [
			'default' => 'single_space',
			'operators' => [
				'=' => 'at_least_single_space',
				'=>' => 'align_single_space_minimal',
			],
		],

		'blank_line_after_namespace' => false,
		'blank_line_after_opening_tag' => false,

		'blank_line_before_statement' => [
			'statements' => [
				'declare',
				'return',
				'throw',
				'try',
			],
		],

		'blank_lines_before_namespace' => false,

		'braces_position' => [
			'classes_opening_brace' => 'same_line',
			'functions_opening_brace' => 'same_line',
			'control_structures_opening_brace' => 'same_line',
		],

		'cast_spaces' => [
			'space' => 'none',
		],

		'class_attributes_separation' => [
			'elements' => [
				'method' => 'one',
			],
		],

		'class_reference_name_casing' => true,
		'clean_namespace' => true,

		'concat_space' => [
			'spacing' => 'one',
		],

		'declare_parentheses' => true,
		'declare_strict_types' => false,

		'echo_tag_syntax' => true,
		'empty_loop_body' => true,
		'empty_loop_condition' => true,

		'explicit_indirect_variable' => true,
		'explicit_string_variable' => true,

		'function_declaration' => [
			'closure_function_spacing' => 'none',
		],

		'general_phpdoc_annotation_remove' => true,
		'general_phpdoc_tag_rename' => true,

		'heredoc_indentation' => true,
		'heredoc_to_nowdoc' => true,

		'indentation_type' => true,

		'increment_style' => [
			'style' => 'post',
		],

		'integer_literal_case' => true,

		'lambda_not_used_import' => true,
		'linebreak_after_opening_tag' => true,
		'list_syntax' => true,

		'magic_constant_casing' => true,
		'magic_method_casing' => true,
		'method_chaining_indentation' => true,
		'multiline_comment_opening_closing' => true,
		'multiline_whitespace_before_semicolons' => true,

		'native_function_casing' => true,
		'native_type_declaration_casing' => true,

		'no_alias_language_construct_call' => true,
		'no_alternative_syntax' => true,
		'no_binary_string' => true,
		'no_blank_lines_after_phpdoc' => true,
		'no_empty_comment' => true,
		'no_empty_phpdoc' => true,
		'no_empty_statement' => true,
		'no_extra_blank_lines' => true,
		'no_leading_namespace_whitespace' => true,
		'no_mixed_echo_print' => true,
		'no_multiline_whitespace_around_double_arrow' => true,
		'no_null_property_initialization' => true,
		'no_short_bool_cast' => true,
		'no_singleline_whitespace_before_semicolons' => true,
		'no_spaces_around_offset' => true,
		'no_trailing_comma_in_singleline' => true,
		'no_trailing_whitespace' => true,
		'no_unneeded_control_parentheses' => true,
		'no_unneeded_import_alias' => true,
		'no_unset_cast' => true,
		'no_unused_imports' => true,
		'no_useless_nullsafe_operator' => true,
		'no_whitespace_before_comma_in_array' => true,

		'normalize_index_brace' => true,
		'object_operator_without_whitespace' => true,
		'operator_linebreak' => true,

		'ordered_imports' => [
			'sort_algorithm' => 'alpha',
		],

		'phpdoc_align' => true,
		'phpdoc_annotation_without_dot' => true,
		'phpdoc_indent' => true,
		'phpdoc_inline_tag_normalizer' => true,
		'phpdoc_line_span' => true,
		'phpdoc_no_access' => true,
		'phpdoc_no_alias_tag' => true,
		'phpdoc_no_useless_inheritdoc' => true,
		'phpdoc_order' => true,
		'phpdoc_order_by_value' => true,
		'phpdoc_param_order' => true,
		'phpdoc_return_self_reference' => true,
		'phpdoc_scalar' => true,
		'phpdoc_separation' => true,
		'phpdoc_single_line_var_spacing' => true,
		'phpdoc_tag_casing' => true,
		'phpdoc_tag_type' => true,
		'phpdoc_to_comment' => true,
		'phpdoc_trim' => true,
		'phpdoc_trim_consecutive_blank_line_separation' => true,
		'phpdoc_types' => true,

		'phpdoc_types_order' => [
			'null_adjustment' => 'always_last',
		],

		'phpdoc_var_annotation_correct_order' => true,
		'phpdoc_var_without_name' => true,

		'semicolon_after_instruction' => true,
		'simple_to_complex_string_variable' => true,
		'single_blank_line_at_eof' => true,
		'single_line_comment_style' => true,
		'single_line_throw' => true,
		'single_space_around_construct' => true,
		'space_after_semicolon' => true,
		'standardize_not_equals' => true,

		'trailing_comma_in_multiline' => false,
		'trim_array_spaces' => true,
		'type_declaration_spaces' => true,
		'types_spaces' => true,
		'unary_operator_spaces' => true,

		'void_return' => false,

		'whitespace_after_comma_in_array' => true,
	])
	->setFinder(
		PhpCsFixer\Finder::create()
			->in(__DIR__ . '/upload/')
			->exclude([
				'system/storage/vendor',
				'system/storage/modification',
				'system/storage/cache',
			])
	);