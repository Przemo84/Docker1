<?php
class ArticleDatatable extends AbstractDatatable
{
    /**
     * {@inheritdoc}
     */
    public function buildDatatable(array $options = array())
    {
        $this->language->set(array(
            'cdn_language_by_locale' => true
        ));

        $this->ajax->set(array(
//            'url' => $this->router->generate('article_index'),
//            'type' => 'GET',
//            'pipeline' => 10
        ));

        $this->options->set(array(
            'classes' => Style::BOOTSTRAP_3_STYLE,
            'individual_filtering' => true,
            'individual_filtering_position' => 'head',
            'order_cells_top' => true,
        ));

        $this->features->set(array());

        $this->columnBuilder
            ->add('id', Column::class, array(
                'title' => 'Id',
                'searchable' => true,
                'orderable' => true,
            ))
            ->add('title', Column::class, array(
                'title' => 'Title',
                'searchable' => true,
                'orderable' => true,
            ))
            ->add('content', Column::class, array(
                'title' => 'Content',
                'searchable' => true,
                'orderable' => true,
            ))
            ->add('image', ImageColumn::class, array(
                'title' => 'Image',
                'imagine_filter' => 'thumbnail_50_x_50',
                'imagine_filter_enlarged' => 'thumbnail_250_x_250',
                'relative_path' => 'images',
                'holder_url' => 'https://placehold.it',
                'enlarge' => true,
            ))
            ->add(null, ActionColumn::class, array(
                'title' => $this->translator->trans('sg.datatables.actions.title'),
                'actions' => array(
                    array(
                        'route' => 'show_article',
                        'route_parameters' => array(
                            'id' => 'id'
                        ),
                        'label' => $this->translator->trans('sg.datatables.actions.show'),
                        'icon' => 'glyphicon glyphicon-eye-open',
                        'attributes' => array(
                            'rel' => 'tooltip',
                            'title' => $this->translator->trans('sg.datatables.actions.show'),
                            'class' => 'btn btn-primary btn-xs',
                            'role' => 'button'
                        ),
                    ),
                    array(
                        'route' => 'update_article',
                        'route_parameters' => array(
                            'id' => 'id'
                        ),
                        'label' => $this->translator->trans('sg.datatables.actions.edit'),
                        'icon' => 'glyphicon glyphicon-edit',
                        'attributes' => array(
                            'rel' => 'tooltip',
                            'title' => $this->translator->trans('sg.datatables.actions.edit'),
                            'class' => 'btn btn-primary btn-xs',
                            'role' => 'button'
                        ),
                    ),
                    array(
                        'route' => 'delete_article',
                        'route_parameters' => array(
                            'id' => 'id'
                        ),
                        'label' => $this->translator->trans('Delete'),
                        'attributes' => array(
//                            'rel' => 'tooltip',
                            'class' => 'btn btn-primary btn-xs',
                            'role' => 'button'
                        ),
                    )
                )
            ));
    }

    /**
     * {@inheritdoc}
     */
    public function getEntity()
    {
        return 'AppBundle\Entity\Article';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'article_datatable';
    }
}