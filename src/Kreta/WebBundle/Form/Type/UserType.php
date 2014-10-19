<?php
/**
 * This file belongs to Kreta.
 * The source code of application includes a LICENSE file
 * with all information about license.
 *
 * @author benatespina <benatespina@gmail.com>
 * @author gorkalaucirica <gorka.lauzirika@gmail.com>
 */

namespace Kreta\WebBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class UserType
 */
class UserType extends AbstractType
{
    /**
     * #{@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', null, array(
                'label' => 'First Name'
            ))
            ->add('lastName', null, array(
                'label' => 'Last Name'
            ));
    }

    /**
     * #{@inheritdoc}
     */
    public function getName()
    {
        return 'kreta_core_user_type';
    }
}
