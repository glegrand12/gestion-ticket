<?php
namespace App\Form;

use App\Entity\Tickets;
use App\Enum\TicketStatusType; // Assurez-vous que c'est bien le bon namespace
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TicketStatusUpdateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'Ouvert' => TicketStatusType::OUVERT,
                    'En cours' => TicketStatusType::EN_COURS,
                    'Résolu' => TicketStatusType::RESOLU,
                    'Fermé' => TicketStatusType::FERME,
                ],
                'choice_label' => function ($choice) {
                    return $choice->label();
                },
                'choice_value' => function ($choice) {
                    return $choice ? $choice->value : null;
                },
                'label' => 'Statut'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tickets::class,
        ]);
    }
}

