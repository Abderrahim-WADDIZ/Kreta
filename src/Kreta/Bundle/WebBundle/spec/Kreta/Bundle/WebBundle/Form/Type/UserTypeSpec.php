<?php

namespace spec\Kreta\Bundle\WebBundle\Form\Type;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Form\FormBuilder;

class UserTypeSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Kreta\Bundle\WebBundle\Form\Type\UserType');
    }

    function it_extends_form_abstract_type()
    {
        $this->shouldHaveType('Symfony\Component\Form\AbstractType');
    }

    function it_builds_a_form(FormBuilder $builder)
    {
        $builder->add('firstName', null, array(
            'label' => 'First Name'
        ))->shouldBeCalled()->willReturn($builder);

        $builder->add('lastName', null, array(
            'label' => 'Last Name'
        ))->shouldBeCalled()->willReturn($builder);

        $builder->add('email', null, array(
            'label' => 'Email'
        ))->shouldBeCalled()->willReturn($builder);

        $this->buildForm($builder, array());
    }

    function it_gets_name()
    {
        $this->getName()->shouldReturn('kreta_core_user_type');
    }
}
