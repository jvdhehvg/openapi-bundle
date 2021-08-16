<?php

declare(strict_types=1);

/*
 * This file is part of the OpenapiBundle package.
 *
 * (c) Niels Nijens <nijens.niels@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nijens\OpenapiBundle\ExceptionHandling\Normalizer;

use Nijens\OpenapiBundle\ExceptionHandling\Exception\ProblemExceptionInterface;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Throwable;

/**
 * Normalizes a {@see Throwable} implementing the {@see ProblemExceptionInterface}.
 *
 * @author Niels Nijens <nijens.niels@gmail.com>
 */
class ProblemExceptionNormalizer implements ContextAwareNormalizerInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;

    private const ALREADY_CALLED = 'nijens_openapi.problem_exception_normalizer.already_called';

    public function normalize($object, $format = null, array $context = [])
    {
        if ($object instanceof ProblemExceptionInterface === false) {
            throw new InvalidArgumentException(sprintf('The object must implement "%s".', ProblemExceptionInterface::class));
        }

        $context[self::ALREADY_CALLED] = true;

        $data = $this->normalizer->normalize($object, $format, $context);

        return array_filter($data);
    }

    public function supportsNormalization($data, $format = null, array $context = []): bool
    {
        if (isset($context[self::ALREADY_CALLED])) {
            return false;
        }

        return $data instanceof ProblemExceptionInterface;
    }
}
