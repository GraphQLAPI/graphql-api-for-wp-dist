<?php

declare (strict_types=1);
namespace PoP\GraphQLParser\FeedbackItemProviders;

use PoP\Root\FeedbackItemProviders\AbstractFeedbackItemProvider;
use PoP\Root\Feedback\FeedbackCategories;
class GraphQLSpecErrorFeedbackItemProvider extends AbstractFeedbackItemProvider
{
    // public final const E_5_1_1 = '5.1.1';
    public const E_5_2_1_1 = '5.2.1.1';
    public const E_5_2_2_1 = '5.2.2.1';
    // public final const E_5_2_3_1 = '5.2.3.1';
    public const E_5_3_1 = '5.3.1';
    public const E_5_3_2 = '5.3.2';
    public const E_5_3_3 = '5.3.3';
    public const E_5_4_1_A = '5.4.1[a]';
    public const E_5_4_1_B = '5.4.1[b]';
    public const E_5_4_2 = '5.4.2';
    public const E_5_4_2_1_A = '5.4.2.1[a]';
    public const E_5_4_2_1_B = '5.4.2.1[b]';
    public const E_5_4_2_1_C = '5.4.2.1[c]';
    public const E_5_4_2_1_D = '5.4.2.1[d]';
    public const E_5_5_1_1 = '5.5.1.1';
    public const E_5_5_1_2 = '5.5.1.2';
    public const E_5_5_1_3 = '5.5.1.3';
    public const E_5_5_1_4 = '5.5.1.4';
    public const E_5_5_2_1 = '5.5.2.1';
    public const E_5_5_2_2 = '5.5.2.2';
    public const E_5_5_2_3 = '5.5.2.3';
    public const E_5_5_2_3_1 = '5.5.2.3.1';
    public const E_5_5_2_3_2 = '5.5.2.3.2';
    public const E_5_5_2_3_3 = '5.5.2.3.3';
    public const E_5_5_2_3_4 = '5.5.2.3.4';
    // public final const E_5_6_1 = '5.6.1';
    public const E_5_6_2 = '5.6.2';
    public const E_5_6_3 = '5.6.3';
    public const E_5_6_4_A = '5.6.4[a]';
    public const E_5_6_4_B = '5.6.4[b]';
    public const E_5_7_1 = '5.7.1';
    public const E_5_7_2 = '5.7.2';
    public const E_5_7_3 = '5.7.3';
    public const E_5_8_1 = '5.8.1';
    public const E_5_8_2 = '5.8.2';
    public const E_5_8_3 = '5.8.3';
    public const E_5_8_4 = '5.8.4';
    public const E_5_8_5 = '5.8.5';
    public const E_6_1_A = '6.1[a]';
    public const E_6_1_B = '6.1[b]';
    public const E_6_1_C = '6.1[c]';
    public const E_6_1_D = '6.1[d]';
    protected function getNamespace() : string
    {
        return 'gql';
    }
    /**
     * @return string[]
     */
    public function getCodes() : array
    {
        return [
            // self::E_5_1_1,
            self::E_5_2_1_1,
            self::E_5_2_2_1,
            // self::E_5_2_3_1,
            self::E_5_3_1,
            self::E_5_3_2,
            self::E_5_3_3,
            self::E_5_4_1_A,
            self::E_5_4_1_B,
            self::E_5_4_2,
            self::E_5_4_2_1_A,
            self::E_5_4_2_1_B,
            self::E_5_4_2_1_C,
            self::E_5_4_2_1_D,
            self::E_5_5_1_1,
            self::E_5_5_1_2,
            self::E_5_5_1_3,
            self::E_5_5_1_4,
            self::E_5_5_2_1,
            self::E_5_5_2_2,
            self::E_5_5_2_3,
            self::E_5_5_2_3_1,
            self::E_5_5_2_3_2,
            self::E_5_5_2_3_3,
            self::E_5_5_2_3_4,
            // self::E_5_6_1,
            self::E_5_6_2,
            self::E_5_6_3,
            self::E_5_6_4_A,
            self::E_5_6_4_B,
            self::E_5_7_1,
            self::E_5_7_2,
            self::E_5_7_3,
            self::E_5_8_1,
            self::E_5_8_2,
            self::E_5_8_3,
            self::E_5_8_4,
            self::E_5_8_5,
            self::E_6_1_A,
            self::E_6_1_B,
            self::E_6_1_C,
            self::E_6_1_D,
        ];
    }
    /**
     * @param string $code
     */
    public function getMessagePlaceholder($code) : string
    {
        switch ($code) {
            case self::E_5_2_1_1:
                return $this->__('Operation name \'%s\' is duplicated', 'graphql-server');
            case self::E_5_2_2_1:
                return $this->__('When the document contains more than one operation, there can be no anonymous operation', 'graphql-server');
            case self::E_5_3_1:
                return $this->__('There is no field \'%s\' on type \'%s\'', 'graphql-parser');
            case self::E_5_3_2:
                return $this->__('The response for field \'%s\' on object with ID \'%s\' is ambiguous, as the previous (and non equivalent) field \'%s\' must also be printed under key \'%s\'', 'graphql-parser');
            case self::E_5_3_3:
                return $this->__('Field \'%s\' from type \'%s\' is not a connection', 'graphql-server');
            case self::E_5_4_1_A:
                return $this->__('On field \'%1$s\' of type \'%2$s\', there is no argument with name \'%3$s\'', 'graphql-server');
            case self::E_5_4_1_B:
                return $this->__('On directive \'%1$s\', there is no argument with name \'%2$s\'', 'graphql-server');
            case self::E_5_4_2:
                return $this->__('Argument \'%s\' is duplicated', 'graphql-server');
            case self::E_5_4_2_1_A:
                return $this->__('Mandatory argument \'%1$s\' in field \'%2$s\' of type \'%3$s\' has not been provided', 'graphql-server');
            case self::E_5_4_2_1_B:
                return $this->__('Argument \'%1$s\' in field \'%2$s\' of type \'%3$s\' cannot be null', 'graphql-server');
            case self::E_5_4_2_1_C:
                return $this->__('Mandatory argument \'%1$s\' in directive \'%2$s\' has not been provided', 'graphql-server');
            case self::E_5_4_2_1_D:
                return $this->__('Argument \'%1$s\' in directive \'%2$s\' cannot be null', 'graphql-server');
            case self::E_5_5_1_1:
                return $this->__('Fragment name \'%s\' is duplicated', 'graphql-server');
            case self::E_5_5_1_2:
                return $this->__('Fragment spread type \'%s\' is not defined in the schema', 'graphql-server');
            case self::E_5_5_1_3:
                return $this->__('The target type of fragment\'%s\' must have kind UNION, INTERFACE, or OBJECT.', 'graphql-server');
            case self::E_5_5_1_4:
                return $this->__('Fragment \'%s\' is not used', 'graphql-server');
            case self::E_5_5_2_1:
                return $this->__('Fragment \'%s\' is not defined in document', 'graphql-server');
            case self::E_5_5_2_2:
                return $this->__('Fragment \'%s\' is cyclical', 'graphql-server');
            case self::E_5_5_2_3:
                return 'TODO: satisfy';
            case self::E_5_5_2_3_1:
                return 'TODO: satisfy';
            case self::E_5_5_2_3_2:
                return 'TODO: satisfy';
            case self::E_5_5_2_3_3:
                return 'TODO: satisfy';
            case self::E_5_5_2_3_4:
                return 'TODO: satisfy';
            case self::E_5_6_2:
                return $this->__('There is no input field \'%s\' in input object \'%s\'', 'graphql-server');
            case self::E_5_6_3:
                return $this->__('Input object has duplicate key \'%s\'', 'graphql-server');
            case self::E_5_6_4_A:
                return $this->__('Mandatory input field \'%s\' in input object \'%s\' has not been provided', 'graphql-server');
            case self::E_5_6_4_B:
                return $this->__('Input field \'%s\' in input object \'%s\' cannot be null', 'graphql-server');
            case self::E_5_7_1:
                return $this->__('There is no directive with name \'%s\'', 'graphql-parser');
            case self::E_5_7_2:
                return $this->__('Directive \'%s\' is not supported at this directive location, or for this node in the GraphQL query', 'graphql-server');
            case self::E_5_7_3:
                return $this->__('Directive \'%s\' can be executed only once', 'graphql-parser');
            case self::E_5_8_1:
                return $this->__('Variable name \'%s\' is duplicated', 'graphql-server');
            case self::E_5_8_2:
                return $this->__('Variable \'%s\' must be of Input type, but type \'%s\' is not (UNION, INTERFACE, or OBJECT types are not allowed)', 'graphql-server');
            case self::E_5_8_3:
                return $this->__('Variable \'%s\' has not been defined in the operation', 'graphql-server');
            case self::E_5_8_4:
                return $this->__('Variable \'%s\' is not used', 'graphql-server');
            case self::E_5_8_5:
                return $this->__('Value is not set for non-nullable variable \'%s\'', 'graphql-server');
            case self::E_6_1_A:
                return $this->__('Operation with name \'%s\' does not exist', 'graphql-server');
            case self::E_6_1_B:
                return $this->__('When the document contains more than one operation, the operation name to execute must be provided', 'graphql-server');
            case self::E_6_1_C:
                return $this->__('The document is empty', 'graphql-server');
            case self::E_6_1_D:
                return $this->__('No operations defined in the document', 'graphql-server');
            default:
                return parent::getMessagePlaceholder($code);
        }
    }
    /**
     * @param string $code
     */
    public function getCategory($code) : string
    {
        return FeedbackCategories::ERROR;
    }
    /**
     * @param string $code
     */
    public function getSpecifiedByURL($code) : ?string
    {
        switch ($code) {
            case self::E_5_2_1_1:
                return 'https://spec.graphql.org/draft/#sec-Operation-Name-Uniqueness';
            case self::E_5_2_2_1:
                return 'https://spec.graphql.org/draft/#sec-Lone-Anonymous-Operation';
            case self::E_5_3_1:
                return 'https://spec.graphql.org/draft/#sec-Field-Selections';
            case self::E_5_3_2:
                return 'https://spec.graphql.org/draft/#sec-Field-Selection-Merging';
            case self::E_5_3_3:
                return 'https://spec.graphql.org/draft/#sec-Leaf-Field-Selections';
            case self::E_5_4_1_A:
            case self::E_5_4_1_B:
                return 'https://spec.graphql.org/draft/#sec-Argument-Names';
            case self::E_5_4_2:
                return 'https://spec.graphql.org/draft/#sec-Argument-Uniqueness';
            case self::E_5_4_2_1_A:
            case self::E_5_4_2_1_B:
            case self::E_5_4_2_1_C:
            case self::E_5_4_2_1_D:
                return 'https://spec.graphql.org/draft/#sec-Required-Arguments';
            case self::E_5_5_1_1:
                return 'https://spec.graphql.org/draft/#sec-Fragment-Name-Uniqueness';
            case self::E_5_5_1_2:
                return 'https://spec.graphql.org/draft/#sec-Fragment-Spread-Type-Existence';
            case self::E_5_5_1_3:
                return 'https://spec.graphql.org/draft/#sec-Fragments-On-Composite-Types';
            case self::E_5_5_1_4:
                return 'https://spec.graphql.org/draft/#sec-Fragments-Must-Be-Used';
            case self::E_5_5_2_1:
                return 'https://spec.graphql.org/draft/#sec-Fragment-spread-target-defined';
            case self::E_5_5_2_2:
                return 'https://spec.graphql.org/draft/#sec-Fragment-spreads-must-not-form-cycles';
            case self::E_5_5_2_3:
                return 'https://spec.graphql.org/draft/#sec-Fragment-spread-is-possible';
            case self::E_5_5_2_3_1:
                return 'https://spec.graphql.org/draft/#sec-Object-Spreads-In-Object-Scope';
            case self::E_5_5_2_3_2:
                return 'https://spec.graphql.org/draft/#sec-Abstract-Spreads-in-Object-Scope';
            case self::E_5_5_2_3_3:
                return 'https://spec.graphql.org/draft/#sec-Object-Spreads-In-Abstract-Scope';
            case self::E_5_5_2_3_4:
                return 'https://spec.graphql.org/draft/#sec-Abstract-Spreads-in-Abstract-Scope';
            case self::E_5_6_2:
                return 'https://spec.graphql.org/draft/#sec-Input-Object-Field-Names';
            case self::E_5_6_3:
                return 'https://spec.graphql.org/draft/#sec-Input-Object-Field-Uniqueness';
            case self::E_5_6_4_A:
            case self::E_5_6_4_B:
                return 'https://spec.graphql.org/draft/#sec-Input-Object-Required-Fields';
            case self::E_5_7_1:
                return 'https://spec.graphql.org/draft/#sec-Directives-Are-Defined';
            case self::E_5_7_2:
                return 'https://spec.graphql.org/draft/#sec-Directives-Are-In-Valid-Locations';
            case self::E_5_7_3:
                return 'https://spec.graphql.org/draft/#sec-Directives-Are-Unique-Per-Location';
            case self::E_5_8_1:
                return 'https://spec.graphql.org/draft/#sec-Variable-Uniqueness';
            case self::E_5_8_2:
                return 'https://spec.graphql.org/draft/#sec-Variables-Are-Input-Types';
            case self::E_5_8_3:
                return 'https://spec.graphql.org/draft/#sec-All-Variable-Uses-Defined';
            case self::E_5_8_4:
                return 'https://spec.graphql.org/draft/#sec-All-Variables-Used';
            case self::E_5_8_5:
                return 'https://spec.graphql.org/draft/#sec-All-Variable-Usages-are-Allowed';
            case self::E_6_1_A:
            case self::E_6_1_B:
            case self::E_6_1_C:
            case self::E_6_1_D:
                return 'https://spec.graphql.org/draft/#sec-Executing-Requests';
            default:
                return parent::getSpecifiedByURL($code);
        }
    }
}
