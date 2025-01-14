<?php

namespace Neos\Rector\ContentRepository90\Rules;

use Neos\Rector\Core\FusionProcessing\EelExpressionTransformer;
use Neos\Rector\Core\FusionProcessing\FusionRectorInterface;
use Neos\Rector\Utility\CodeSampleLoader;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

class FusionContextInBackendRector implements FusionRectorInterface
{

    public function getRuleDefinition(): RuleDefinition
    {
        return CodeSampleLoader::fromFile('Fusion: Rewrite node.context.inBackend to Neos.Ui.NodeInfo.inBackend(...)', __CLASS__, 'some_class.fusion');
    }

    public function refactorFileContent(string $fileContent): string
    {
        return EelExpressionTransformer::parse($fileContent)
            ->process(fn(string $eelExpression) => preg_replace(
                '/(node|documentNode|site)\.context\.inBackend/',
                'Neos.Ui.NodeInfo.inBackend($1)',
                $eelExpression
            ))
            ->addWarningsIfRegexMatches(
                '/\.context\.inBackend/',
                '// TODO 9.0 migration: Line %LINE: You very likely need to rewrite "VARIABLE.context.inBackend" to Neos.Ui.NodeInfo.inBackend(VARIABLE). We did not auto-apply this migration because we cannot be sure whether the variable is a Node.'
            )->getProcessedContent();
    }
}
