<?php

/*
 * This file belongs to Kreta.
 * The source code of application includes a LICENSE file
 * with all information about license.
 *
 * @author benatespina <benatespina@gmail.com>
 * @author gorkalaucirica <gorka.lauzirika@gmail.com>
 */

namespace Kreta\Component\Issue\Form\Type;

use Kreta\Component\Core\Form\Type\Abstracts\AbstractType;
use Kreta\Component\Project\Model\Interfaces\ProjectInterface;
use Kreta\Component\Project\Model\IssueType as Type;
use Kreta\Component\Project\Model\Priority;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class IssueType.
 *
 * @package Kreta\Component\Issue\Form\Type
 */
class IssueType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', 'text')
            ->add('description', 'textarea', [
                'required' => false,
            ])
            ->add('project', 'entity', [
                'class'   => 'Kreta\Component\Project\Model\Project',
                'choices' => $options['projects']
            ]);

        $formModifier = function (FormInterface $form, ProjectInterface $project = null) {
            $participants = null === $project ? [] : $project->getParticipants();
            $types = null === $project ? [] : $project->getIssueTypes();
            $priorities = null === $project ? [] : $project->getPriorities();
            $users = [];
            foreach ($participants as $participant) {
                $users[] = $participant->getUser();
            }

            $form
                ->add('assignee', 'entity', [
                    'class'   => 'Kreta\Component\User\Model\User',
                    'choices' => $users
                ])
                ->add('priority', 'entity', [
                    'class'   => 'Kreta\Component\Project\Model\Priority',
                    'choices' => $priorities
                ])
                ->add('type', 'entity', [
                    'class'   => 'Kreta\Component\Project\Model\IssueType',
                    'choices' => $types 
                ]);
        };

        $builder->get('project')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($formModifier) {
                $project = $event->getForm()->getData();
                $formModifier($event->getForm()->getParent(), $project);
            }
        );
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);
        $resolver->setDefaults(['projects' => []]);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'kreta_issue_issue_type';
    }

    /**
     * {@inheritdoc}
     */
    protected function createEmptyData(FormInterface $form)
    {
        $type = null === $form->get('type')->getData() ? new Type() : $form->get('type')->getData();
        $priority = null === $form->get('priority')->getData() ? new Priority() : $form->get('priority')->getData();

        return $this->factory->create($this->user, $type, $priority, $form->get('project')->getData());
    }
}
