<?php

namespace App\Form;

use App\Entity\Tickets;
use App\Entity\Users;
use App\Enum\TicketPriorityType;
use App\Enum\TicketStatusType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TicketsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title',null, ['label' => 'Titre'])
            ->add('description',null, ['label' => 'Description'])
            /*->add('status', ChoiceType::class, [
                'choices' => [
                    'Ouvert' => TicketStatusType::OUVERT,
                    'En cours' => TicketStatusType::EN_COURS,
                    'Résolu' => TicketStatusType::RESOLU,
                    'Fermé' => TicketStatusType::FERME,
                ],
                'choice_label' => function ($choice) {
                    return $choice->label();
                },
                'choice_value' => function (?TicketStatusType $choice) {
                    return $choice?->value;
                },
            ])*/
            ->add('priority', ChoiceType::class, [
                'choices' => [
                    'Basse' => TicketPriorityType::BASSE,
                    'Moyenne' => TicketPriorityType::MOYENNE,
                    'Haute' => TicketPriorityType::HAUTE,
                ],
                'choice_label' => function ($choice) {
                    return $choice->label();
                },
                'choice_value' => function (?TicketPriorityType $choice) {
                    return $choice?->value;
                },
                'label' => 'Priorité'])
            ->add('deadline', null, [
                'widget' => 'single_text',
            ])
            ->add('assignedTo', EntityType::class, [
                'class' => Users::class,
                'choice_label' => 'email',
                'label' => 'Attribuer à'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tickets::class,
        ]);
    }
}
