<?php
/**
 * Created by PhpStorm.
 * @author Ewgeniy Kiselev <demroos@gmail.com>
 * Date: 23.07.19
 * Time: 17:24
 */

namespace Demroos\NotificationBundle\MetaExtractor;

use Demroos\NotificationBundle\Exception\NotificationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;

class DefaultMetaExtractor implements MetaExtractorInterface
{
    /** @var ValidatorInterface */
    protected $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }


    public function extract(Request $request): MetaInfo
    {
        $data = json_decode($request->getContent(), true);

        $dataConstraint = new Assert\Collection([
            'entity' => [
                new Assert\NotBlank(),
                new Assert\NotNull()
            ],
            'payload' => [
                new Assert\Type('array')
            ]
        ]);

        /** @var ConstraintViolationInterface[] $errors */
        $errors = $this->validator->validate($data, $dataConstraint);

        $this->catchErrors($errors);

        return new MetaInfo($data['entity'], $data['payload']);
    }

    protected function catchErrors($errors)
    {
        if (count($errors) > 0) {
            $messages = [];
            foreach ($errors as $error) {
                $messages[] = sprintf('%s -> %s', $error->getPropertyPath(), $error->getMessage());
                //$messages[] = $error->getMessage();
            }

            throw new NotificationException(
                implode('; ', $messages),
                400
            );
        }
    }
}
