<?php


namespace Djoudi\LaravelH5p\H5pCore;


class CustomH5PContentValidator extends \H5PContentValidator {


    /**
     * @return object[]
     */
    public function getMetadataSemantics() {
        static $semantics;

        $cc_versions = array(
            (object) array(
                'value' => '4.0',
                'label' => $this->h5pF->t('4.0 International')
            ),
            (object) array(
                'value' => '3.0',
                'label' => $this->h5pF->t('3.0 Unported')
            ),
            (object) array(
                'value' => '2.5',
                'label' => $this->h5pF->t('2.5 Generic')
            ),
            (object) array(
                'value' => '2.0',
                'label' => $this->h5pF->t('2.0 Generic')
            ),
            (object) array(
                'value' => '1.0',
                'label' => $this->h5pF->t('1.0 Generic')
            )
        );

        $semantics = array(
            (object) array(
                'name' => 'title',
                'type' => 'text',
                'label' => $this->h5pF->t('Title'),
                'placeholder' => 'La Gioconda'
            ),
            (object) array(
                'name' => 'license',
                'type' => 'select',
                'label' => $this->h5pF->t('License'),
                'default' => 'U',
                'options' => array(
                    (object) array(
                        'value' => 'U',
                        'label' => $this->h5pF->t('Undisclosed')
                    ),
                    (object) array(
                        'type' => 'optgroup',
                        'label' => $this->h5pF->t('Creative Commons'),
                        'options' => array(
                            (object) array(
                                'value' => 'CC BY',
                                'label' => $this->h5pF->t('Attribution (CC BY)'),
                                'versions' => $cc_versions
                            ),
                            (object) array(
                                'value' => 'CC BY-SA',
                                'label' => $this->h5pF->t('Attribution-ShareAlike (CC BY-SA)'),
                                'versions' => $cc_versions
                            ),
                            (object) array(
                                'value' => 'CC BY-ND',
                                'label' => $this->h5pF->t('Attribution-NoDerivs (CC BY-ND)'),
                                'versions' => $cc_versions
                            ),
                            (object) array(
                                'value' => 'CC BY-NC',
                                'label' => $this->h5pF->t('Attribution-NonCommercial (CC BY-NC)'),
                                'versions' => $cc_versions
                            ),
                            (object) array(
                                'value' => 'CC BY-NC-SA',
                                'label' => $this->h5pF->t('Attribution-NonCommercial-ShareAlike (CC BY-NC-SA)'),
                                'versions' => $cc_versions
                            ),
                            (object) array(
                                'value' => 'CC BY-NC-ND',
                                'label' => $this->h5pF->t('Attribution-NonCommercial-NoDerivs (CC BY-NC-ND)'),
                                'versions' => $cc_versions
                            ),
                            (object) array(
                                'value' => 'CC0 1.0',
                                'label' => $this->h5pF->t('Public Domain Dedication (CC0)')
                            ),
                            (object) array(
                                'value' => 'CC PDM',
                                'label' => $this->h5pF->t('Public Domain Mark (PDM)')
                            ),
                        )
                    ),
                    (object) array(
                        'value' => 'GNU GPL',
                        'label' => $this->h5pF->t('General Public License v3')
                    ),
                    (object) array(
                        'value' => 'PD',
                        'label' => $this->h5pF->t('Public Domain')
                    ),
                    (object) array(
                        'value' => 'ODC PDDL',
                        'label' => $this->h5pF->t('Public Domain Dedication and Licence')
                    ),
                    (object) array(
                        'value' => 'C',
                        'label' => $this->h5pF->t('Copyright')
                    )
                )
            ),
            (object) array(
                'name' => 'licenseVersion',
                'type' => 'select',
                'label' => $this->h5pF->t('License Version'),
                'options' => $cc_versions,
                'optional' => TRUE
            ),
            (object) array(
                'name' => 'yearFrom',
                'type' => 'number',
                'label' => $this->h5pF->t('Years (from)'),
                'placeholder' => '1991',
                'min' => '-9999',
                'max' => '9999',
                'optional' => TRUE
            ),
            (object) array(
                'name' => 'yearTo',
                'type' => 'number',
                'label' => $this->h5pF->t('Years (to)'),
                'placeholder' => '1992',
                'min' => '-9999',
                'max' => '9999',
                'optional' => TRUE
            ),
            (object) array(
                'name' => 'source',
                'type' => 'text',
                'label' => $this->h5pF->t('Source'),
                'placeholder' => 'https://',
                'optional' => TRUE
            ),
            (object) array(
                'name' => 'royaltyType',
                'type' => 'select',
                'label' => $this->h5pF->t('Royalty Type'),
                'options' => array(
                    (object) array(
                        'value' => 'usage',
                        'label' => $this->h5pF->t('Usage')
                    ),
                    (object) array(
                        'value' => 'monthly',
                        'label' => $this->h5pF->t('Monthly')
                    ),
                    (object) array(
                        'value' => 'yearly',
                        'label' => $this->h5pF->t('Yearly')
                    )
                ),
                'optional' => TRUE
            ),
            (object) array(
                'name' => 'royaltyTermsViews',
                'type' => 'select',
                'label' => $this->h5pF->t('Royalty Terms Views'),
                'options' => array(
                    (object) array(
                        'value' => '500',
                        'label' => $this->h5pF->t('500')
                    ),
                    (object) array(
                        'value' => '1000',
                        'label' => $this->h5pF->t('1000')
                    ),
                    (object) array(
                        'value' => '5000',
                        'label' => $this->h5pF->t('5000')
                    )
                ),
                'optional' => TRUE
            ),
            (object) array(
                'name' => 'currency',
                'type' => 'select',
                'label' => $this->h5pF->t('Currency'),
                'options' => array(
                    (object) array(
                        'value' => 'USD',
                        'label' => $this->h5pF->t('USD')
                    ),
                    (object) array(
                        'value' => 'EURO',
                        'label' => $this->h5pF->t('EURO')
                    )
                ),
                'optional' => TRUE
            ),
            (object) array(
                'name' => 'amount',
                'type' => 'number',
                'label' => $this->h5pF->t('Amount'),
                'optional' => TRUE
            ),
            (object) array(
                'name' => 'copyrightNotice',
                'type' => 'text',
                'widget' => 'textarea',
                'label' => $this->h5pF->t('Copyright Notice'),
                'optional' => TRUE,
                'description' => $this->h5pF->t('Any additional information about the copyright notice')
            ),
            (object) array(
                'name' => 'creditText',
                'type' => 'text',
                'widget' => 'textarea',
                'label' => $this->h5pF->t('Credit Note'),
                'optional' => TRUE,
                'description' => $this->h5pF->t('Any additional information about the credit note')
            ),
            (object) array(
                'name' => 'authors',
                'type' => 'list',
                'field' => (object) array (
                    'name' => 'author',
                    'type' => 'group',
                    'fields'=> array(
                        (object) array(
                            'label' => $this->h5pF->t("Author's name"),
                            'name' => 'name',
                            'optional' => TRUE,
                            'type' => 'text'
                        ),
                        (object) array(
                            'name' => 'role',
                            'type' => 'select',
                            'label' => $this->h5pF->t("Author's role"),
                            'default' => 'Author',
                            'options' => array(
                                (object) array(
                                    'value' => 'Author',
                                    'label' => $this->h5pF->t('Author')
                                ),
                                (object) array(
                                    'value' => 'Editor',
                                    'label' => $this->h5pF->t('Editor')
                                ),
                                (object) array(
                                    'value' => 'Licensee',
                                    'label' => $this->h5pF->t('Licensee')
                                ),
                                (object) array(
                                    'value' => 'Originator',
                                    'label' => $this->h5pF->t('Originator')
                                )
                            )
                        )
                    )
                )
            ),
            (object) array(
                'name' => 'licenseExtras',
                'type' => 'text',
                'widget' => 'textarea',
                'label' => $this->h5pF->t('License Extras'),
                'optional' => TRUE,
                'description' => $this->h5pF->t('Any additional information about the license')
            ),
            (object) array(
                'name' => 'changes',
                'type' => 'list',
                'field' => (object) array(
                    'name' => 'change',
                    'type' => 'group',
                    'label' => $this->h5pF->t('Changelog'),
                    'fields' => array(
                        (object) array(
                            'name' => 'date',
                            'type' => 'text',
                            'label' => $this->h5pF->t('Date'),
                            'optional' => TRUE
                        ),
                        (object) array(
                            'name' => 'author',
                            'type' => 'text',
                            'label' => $this->h5pF->t('Changed by'),
                            'optional' => TRUE
                        ),
                        (object) array(
                            'name' => 'log',
                            'type' => 'text',
                            'widget' => 'textarea',
                            'label' => $this->h5pF->t('Description of change'),
                            'placeholder' => $this->h5pF->t('Photo cropped, text changed, etc.'),
                            'optional' => TRUE
                        )
                    )
                )
            ),
            (object) array (
                'name' => 'authorComments',
                'type' => 'text',
                'widget' => 'textarea',
                'label' => $this->h5pF->t('Author comments'),
                'description' => $this->h5pF->t('Comments for the editor of the content (This text will not be published as a part of copyright info)'),
                'optional' => TRUE
            ),
            (object) array(
                'name' => 'contentType',
                'type' => 'text',
                'widget' => 'none'
            ),
            (object) array(
                'name' => 'defaultLanguage',
                'type' => 'text',
                'widget' => 'none'
            )
        );

        return $semantics;
    }


}
