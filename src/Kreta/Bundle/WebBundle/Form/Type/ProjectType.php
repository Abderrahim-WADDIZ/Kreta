<?php

/**
 * This file belongs to Kreta.
 * The source code of application includes a LICENSE file
 * with all information about license.
 *
 * @author benatespina <benatespina@gmail.com>
 * @author gorkalaucirica <gorka.lauzirika@gmail.com>
 */

namespace Kreta\Bundle\WebBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class ProjectType.
 *
 * @package Kreta\Bundle\WebBundle\Form\Type
 */
class ProjectType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array(
                'required' => true,
                'label'    => 'Name',
            ))
            ->add('shortName', 'text', array(
                'label' => 'Short name',
                'attr' => array('maxlength' => 4)
            ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'kreta_core_project_type';
    }
}
