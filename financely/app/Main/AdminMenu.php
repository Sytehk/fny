<?php

namespace App\Main;

class AdminMenu
{
    /**
     * List of side menu items.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public static function menu()
    {
        return [
            'dashboard' => [
                'icon' => 'home',
                'route_name' => 'adminIndex',
                'params' => [
                    'layout' => 'admin-menu',
                ],
                'title' =>'Dashboard'
            ],
            'users' => [
                'icon' => 'users',
                'title' => 'Users',
                'sub_menu' => [
                    'all-users' => [
                        'icon' => '',
                        'route_name' => 'users-layout-1',
                        'params' => [
                            'layout' => 'admin-menu'
                        ],
                        'title' => 'All Users'
                    ],
                    'active-users' => [
                        'icon' => '',
                        'route_name' => 'users-layout-2',
                        'params' => [
                            'layout' => 'admin-menu'
                        ],
                        'title' => 'Active Users'
                    ],
                    'banned-users' => [
                        'icon' => '',
                        'route_name' => 'users-layout-2',
                        'params' => [
                            'layout' => 'admin-menu'
                        ],
                        'title' => 'Active Users'
                    ],
                    'create-users' => [
                        'icon' => '',
                        'route_name' => 'users-layout-3',
                        'params' => [
                            'layout' => 'admin-menu'
                        ],
                        'title' => 'Create Users'
                    ]
                ]
            ],
            'notifications' => [
                'icon' => 'inbox',
                'title' => 'Notifications',
                'sub_menu' => [
                    'new-blog' => [
                        'icon' => '',
                        'route_name' => 'users-layout-2',
                        'params' => [
                            'layout' => 'admin-menu'
                        ],
                        'title' => 'New Blog'
                    ],
                    'manage-blogs' => [
                        'icon' => '',
                        'route_name' => 'users-layout-2',
                        'params' => [
                            'layout' => 'admin-menu'
                        ],
                        'title' => 'Manage Blogs'
                    ],
                    'message-inbox' => [
                        'icon' => '',
                        'route_name' => 'inbox',
                        'params' => [
                            'layout' => 'admin-menu'
                        ],
                        'title' => 'Message Inbox'
                    ],
                    'new-email' => [
                        'icon' => '',
                        'route_name' => 'users-layout-2',
                        'params' => [
                            'layout' => 'admin-menu'
                        ],
                        'title' => 'New Email'
                    ],
                    'system-message' => [
                        'icon' => '',
                        'route_name' => 'users-layout-2',
                        'params' => [
                            'layout' => 'admin-menu'
                        ],
                        'title' => 'System Message'
                    ]
                ]
            ],
            'support-tickets' => [
                'icon' => 'aperture',
                'title' => 'Support Tickets',
                'sub_menu' => [
                    'active-tickets' => [
                        'icon' => '',
                        'route_name' => 'users-layout-1',
                        'params' => [
                            'layout' => 'admin-menu'
                        ],
                        'title' => 'Active Tickets'
                    ],
                    'closed-tickets' => [
                        'icon' => '',
                        'route_name' => 'users-layout-3',
                        'params' => [
                            'layout' => 'admin-menu'
                        ],
                        'title' => 'Closed Tickets'
                    ]
                ]
            ],
            'manage-investments' => [
                'icon' => 'aperture',
                'title' => 'Manage Investments',
                'sub_menu' => [
                    'investment-plans' => [
                        'icon' => '',
                        'route_name' => 'users-layout-1',
                        'params' => [
                            'layout' => 'admin-menu'
                        ],
                        'title' => 'Investment Plans'
                    ],
                    'create-new-plan' => [
                        'icon' => '',
                        'route_name' => 'users-layout-1',
                        'params' => [
                            'layout' => 'admin-menu'
                        ],
                        'title' => 'Create New Plan'
                    ],
                    'user-investments' => [
                        'icon' => '',
                        'route_name' => 'users-layout-3',
                        'params' => [
                            'layout' => 'admin-menu'
                        ],
                        'title' => 'User Investments'
                    ]
                ]
            ],
            'manage-deposits' => [
                'icon' => 'aperture',
                'title' => 'Manage Deposits',
                'sub_menu' => [
                    'online-deposits' => [
                        'icon' => '',
                        'route_name' => 'users-layout-1',
                        'params' => [
                            'layout' => 'admin-menu'
                        ],
                        'title' => 'Online Deposits'
                    ],
                    'offline-deposits' => [
                        'icon' => '',
                        'route_name' => 'users-layout-3',
                        'params' => [
                            'layout' => 'admin-menu'
                        ],
                        'title' => 'Offline Deposits'
                    ]
                ]
            ],
            'fund-withdrawal' => [
                'icon' => 'aperture',
                'title' => 'Fund Withdrawal',
                'sub_menu' => [
                    'all-withdrawals' => [
                        'icon' => '',
                        'route_name' => 'users-layout-1',
                        'params' => [
                            'layout' => 'admin-menu'
                        ],
                        'title' => 'All Withdrawals'
                    ],
                    'pending-withdrawals' => [
                        'icon' => '',
                        'route_name' => 'users-layout-3',
                        'params' => [
                            'layout' => 'admin-menu'
                        ],
                        'title' => 'Pending Withdrawals'
                    ]
                ]
            ],
            'loan-management' => [
                'icon' => 'aperture',
                'title' => 'Support Tickets',
                'sub_menu' => [
                    'loan-plans' => [
                        'icon' => '',
                        'route_name' => 'users-layout-1',
                        'params' => [
                            'layout' => 'admin-menu'
                        ],
                        'title' => 'Loan Plans'
                    ],
                    'create-new-plan' => [
                        'icon' => '',
                        'route_name' => 'users-layout-1',
                        'params' => [
                            'layout' => 'admin-menu'
                        ],
                        'title' => 'Create New Plan'
                    ],
                    'pending-loan-requests' => [
                        'icon' => '',
                        'route_name' => 'users-layout-1',
                        'params' => [
                            'layout' => 'admin-menu'
                        ],
                        'title' => 'Pending Loan Requests'
                    ],
                    'disburse-loan' => [
                        'icon' => '',
                        'route_name' => 'users-layout-1',
                        'params' => [
                            'layout' => 'admin-menu'
                        ],
                        'title' => 'Disburse Loan'
                    ],
                    'active-user-loans' => [
                        'icon' => '',
                        'route_name' => 'users-layout-3',
                        'params' => [
                            'layout' => 'admin-menu'
                        ],
                        'title' => 'Active User Loans'
                    ]
                ]
            ],
            'manage-gift-cards' => [
                'icon' => 'aperture',
                'title' => 'Manage Gift Cards',
                'sub_menu' => [
                    'bought-cards' => [
                        'icon' => '',
                        'route_name' => 'users-layout-1',
                        'params' => [
                            'layout' => 'admin-menu'
                        ],
                        'title' => 'Bought Cards'
                    ],
                    'sales-request' => [
                        'icon' => '',
                        'route_name' => 'users-layout-1',
                        'params' => [
                            'layout' => 'admin-menu'
                        ],
                        'title' => 'Sales Request'
                    ],
                    'sold-cards' => [
                        'icon' => '',
                        'route_name' => 'users-layout-1',
                        'params' => [
                            'layout' => 'admin-menu'
                        ],
                        'title' => 'Sold Cards'
                    ],
                    'purchase-requests' => [
                        'icon' => '',
                        'route_name' => 'users-layout-3',
                        'params' => [
                            'layout' => 'admin-menu'
                        ],
                        'title' => 'Purchase Requests'
                    ]
                ]
            ],
            'manage-crypto' => [
                'icon' => 'aperture',
                'title' => 'Manage Crypto',
                'sub_menu' => [
                    'bought-cryptos' => [
                        'icon' => '',
                        'route_name' => 'users-layout-1',
                        'params' => [
                            'layout' => 'admin-menu'
                        ],
                        'title' => 'Bought Cryptos'
                    ],
                    'sales-request' => [
                        'icon' => '',
                        'route_name' => 'users-layout-1',
                        'params' => [
                            'layout' => 'admin-menu'
                        ],
                        'title' => 'Sales Request'
                    ],
                    'sold-cards' => [
                        'icon' => '',
                        'route_name' => 'users-layout-1',
                        'params' => [
                            'layout' => 'admin-menu'
                        ],
                        'title' => 'Sold Cryptos'
                    ],
                    'purchase-requests' => [
                        'icon' => '',
                        'route_name' => 'users-layout-3',
                        'params' => [
                            'layout' => 'admin-menu'
                        ],
                        'title' => 'Purchase Requests'
                    ]
                ]
            ],
            'manage-coins' => [
                'icon' => 'aperture',
                'title' => 'Manage Virtual Coins',
                'sub_menu' => [
                    'manage-coins' => [
                        'icon' => '',
                        'route_name' => 'users-layout-1',
                        'params' => [
                            'layout' => 'admin-menu'
                        ],
                        'title' => 'Manage Coins'
                    ],
                    'create-coins' => [
                        'icon' => '',
                        'route_name' => 'users-layout-1',
                        'params' => [
                            'layout' => 'admin-menu'
                        ],
                        'title' => 'Create Coins'
                    ],
                    'sold-coin-units' => [
                        'icon' => '',
                        'route_name' => 'users-layout-1',
                        'params' => [
                            'layout' => 'admin-menu'
                        ],
                        'title' => 'Sold Coin Units'
                    ],
                    'user-sold-coins' => [
                        'icon' => '',
                        'route_name' => 'users-layout-3',
                        'params' => [
                            'layout' => 'admin-menu'
                        ],
                        'title' => 'User Sold Coins'
                    ]
                ]
            ],
            'kyc-manager' => [
                'icon' => 'inbox',
                'route_name' => 'inbox',
                'params' => [
                    'layout' => 'admin-menu'
                ],
                'title' => 'KYC Manager'
            ],
            'fund-transfer-logs' => [
                'icon' => 'hard-drive',
                'route_name' => 'file-manager',
                'params' => [
                    'layout' => 'admin-menu'
                ],
                'title' => 'Funds Transfer Logs'
            ],
            'manage-coins' => [
                'icon' => 'aperture',
                'title' => 'Manage Virtual Coins',
                'sub_menu' => [
                    'manage-coins' => [
                        'icon' => '',
                        'route_name' => 'users-layout-1',
                        'params' => [
                            'layout' => 'admin-menu'
                        ],
                        'title' => 'Manage Coins'
                    ],
                    'create-coins' => [
                        'icon' => '',
                        'route_name' => 'users-layout-1',
                        'params' => [
                            'layout' => 'admin-menu'
                        ],
                        'title' => 'Create Coins'
                    ],
                    'sold-coin-units' => [
                        'icon' => '',
                        'route_name' => 'users-layout-1',
                        'params' => [
                            'layout' => 'admin-menu'
                        ],
                        'title' => 'Sold Coin Units'
                    ],
                    'user-sold-coins' => [
                        'icon' => '',
                        'route_name' => 'users-layout-3',
                        'params' => [
                            'layout' => 'admin-menu'
                        ],
                        'title' => 'User Sold Coins'
                    ]
                ]
            ],
            'system-settings' => [
                'icon' => 'credit-card',
                'route_name' => 'point-of-sale',
                'params' => [
                    'layout' => 'admin-menu'
                ],
                'title' => 'System Settings'
            ],
            'chat' => [
                'icon' => 'message-square',
                'route_name' => 'chat',
                'params' => [
                    'layout' => 'admin-menu'
                ],
                'title' => 'Chat'
            ],
            'post' => [
                'icon' => 'file-text',
                'route_name' => 'post',
                'params' => [
                    'layout' => 'admin-menu'
                ],
                'title' => 'Post'
            ],
            'calendar' => [
                'icon' => 'calendar',
                'route_name' => 'calendar',
                'params' => [
                    'layout' => 'admin-menu'
                ],
                'title' => 'Calendar'
            ],
            'devider',
            'crud' => [
                'icon' => 'edit',
                'title' => 'Crud',
                'sub_menu' => [
                    'crud-data-list' => [
                        'icon' => '',
                        'route_name' => 'crud-data-list',
                        'params' => [
                            'layout' => 'admin-menu'
                        ],
                        'title' => 'Data List'
                    ],
                    'crud-form' => [
                        'icon' => '',
                        'route_name' => 'crud-form',
                        'params' => [
                            'layout' => 'admin-menu'
                        ],
                        'title' => 'Form'
                    ]
                ]
            ],
            'profile' => [
                'icon' => 'trello',
                'title' => 'Profile',
                'sub_menu' => [
                    'profile-overview-1' => [
                        'icon' => '',
                        'route_name' => 'profile-overview-1',
                        'params' => [
                            'layout' => 'admin-menu'
                        ],
                        'title' => 'Overview 1'
                    ],
                    'profile-overview-2' => [
                        'icon' => '',
                        'route_name' => 'profile-overview-2',
                        'params' => [
                            'layout' => 'admin-menu'
                        ],
                        'title' => 'Overview 2'
                    ],
                    'profile-overview-3' => [
                        'icon' => '',
                        'route_name' => 'profile-overview-3',
                        'params' => [
                            'layout' => 'admin-menu'
                        ],
                        'title' => 'Overview 3'
                    ]
                ]
            ],
            'pages' => [
                'icon' => 'layout',
                'title' => 'Pages',
                'sub_menu' => [
                    'wizards' => [
                        'icon' => '',
                        'title' => 'Wizards',
                        'sub_menu' => [
                            'wizard-layout-1' => [
                                'icon' => '',
                                'route_name' => 'wizard-layout-1',
                                'params' => [
                                    'layout' => 'admin-menu'
                                ],
                                'title' => 'Layout 1'
                            ],
                            'wizard-layout-2' => [
                                'icon' => '',
                                'route_name' => 'wizard-layout-2',
                                'params' => [
                                    'layout' => 'admin-menu'
                                ],
                                'title' => 'Layout 2'
                            ],
                            'wizard-layout-3' => [
                                'icon' => '',
                                'route_name' => 'wizard-layout-3',
                                'params' => [
                                    'layout' => 'admin-menu'
                                ],
                                'title' => 'Layout 3'
                            ]
                        ]
                    ],
                    'blog' => [
                        'icon' => '',
                        'title' => 'Blog',
                        'sub_menu' => [
                            'blog-layout-1' => [
                                'icon' => '',
                                'route_name' => 'blog-layout-1',
                                'params' => [
                                    'layout' => 'admin-menu'
                                ],
                                'title' => 'Layout 1'
                            ],
                            'blog-layout-2' => [
                                'icon' => '',
                                'route_name' => 'blog-layout-2',
                                'params' => [
                                    'layout' => 'admin-menu'
                                ],
                                'title' => 'Layout 2'
                            ],
                            'blog-layout-3' => [
                                'icon' => '',
                                'route_name' => 'blog-layout-3',
                                'params' => [
                                    'layout' => 'admin-menu'
                                ],
                                'title' => 'Layout 3'
                            ]
                        ]
                    ],
                    'pricing' => [
                        'icon' => '',
                        'title' => 'Pricing',
                        'sub_menu' => [
                            'pricing-layout-1' => [
                                'icon' => '',
                                'route_name' => 'pricing-layout-1',
                                'params' => [
                                    'layout' => 'admin-menu'
                                ],
                                'title' => 'Layout 1'
                            ],
                            'pricing-layout-2' => [
                                'icon' => '',
                                'route_name' => 'pricing-layout-2',
                                'params' => [
                                    'layout' => 'admin-menu'
                                ],
                                'title' => 'Layout 2'
                            ]
                        ]
                    ],
                    'invoice' => [
                        'icon' => '',
                        'title' => 'Invoice',
                        'sub_menu' => [
                            'invoice-layout-1' => [
                                'icon' => '',
                                'route_name' => 'invoice-layout-1',
                                'params' => [
                                    'layout' => 'admin-menu'
                                ],
                                'title' => 'Layout 1'
                            ],
                            'invoice-layout-2' => [
                                'icon' => '',
                                'route_name' => 'invoice-layout-2',
                                'params' => [
                                    'layout' => 'admin-menu'
                                ],
                                'title' => 'Layout 2'
                            ]
                        ]
                    ],
                    'faq' => [
                        'icon' => '',
                        'title' => 'FAQ',
                        'sub_menu' => [
                            'faq-layout-1' => [
                                'icon' => '',
                                'route_name' => 'faq-layout-1',
                                'params' => [
                                    'layout' => 'admin-menu'
                                ],
                                'title' => 'Layout 1'
                            ],
                            'faq-layout-2' => [
                                'icon' => '',
                                'route_name' => 'faq-layout-2',
                                'params' => [
                                    'layout' => 'admin-menu'
                                ],
                                'title' => 'Layout 2'
                            ],
                            'faq-layout-3' => [
                                'icon' => '',
                                'route_name' => 'faq-layout-3',
                                'params' => [
                                    'layout' => 'admin-menu'
                                ],
                                'title' => 'Layout 3'
                            ]
                        ]
                    ],
                    'login' => [
                        'icon' => '',
                        'route_name' => 'login',
                        'params' => [
                            'layout' => 'login'
                        ],
                        'title' => 'Login'
                    ],
                    'register' => [
                        'icon' => '',
                        'route_name' => 'register',
                        'params' => [
                            'layout' => 'login'
                        ],
                        'title' => 'Register'
                    ],
                    'error-page' => [
                        'icon' => '',
                        'route_name' => 'error-page',
                        'params' => [
                            'layout' => 'admin-menu'
                        ],
                        'title' => 'Error Page'
                    ],
                    'update-profile' => [
                        'icon' => '',
                        'route_name' => 'update-profile',
                        'params' => [
                            'layout' => 'admin-menu'
                        ],
                        'title' => 'Update profile'
                    ],
                    'change-password' => [
                        'icon' => '',
                        'route_name' => 'change-password',
                        'params' => [
                            'layout' => 'admin-menu'
                        ],
                        'title' => 'Change Password'
                    ]
                ]
            ],
            'devider',
            'components' => [
                'icon' => 'inbox',
                'title' => 'Components',
                'sub_menu' => [
                    'grid' => [
                        'icon' => '',
                        'title' => 'Grid',
                        'sub_menu' => [
                            'regular-table' => [
                                'icon' => '',
                                'route_name' => 'regular-table',
                                'params' => [
                                    'layout' => 'admin-menu'
                                ],
                                'title' => 'Regular Table'
                            ],
                            'tabulator' => [
                                'icon' => '',
                                'route_name' => 'tabulator',
                                'params' => [
                                    'layout' => 'admin-menu'
                                ],
                                'title' => 'Tabulator'
                            ]
                        ]
                    ],
                    'overlay' => [
                        'icon' => '',
                        'title' => 'Overlay',
                        'sub_menu' => [
                            'modal' => [
                                'icon' => '',
                                'route_name' => 'modal',
                                'params' => [
                                    'layout' => 'admin-menu'
                                ],
                                'title' => 'Modal'
                            ],
                            'slide-over' => [
                                'icon' => '',
                                'route_name' => 'slide-over',
                                'params' => [
                                    'layout' => 'admin-menu'
                                ],
                                'title' => 'Slide Over'
                            ],
                            'notification' => [
                                'icon' => '',
                                'route_name' => 'notification',
                                'params' => [
                                    'layout' => 'admin-menu'
                                ],
                                'title' => 'Notification'
                            ],
                        ]
                    ],
                    'accordion' => [
                        'icon' => '',
                        'route_name' => 'accordion',
                        'params' => [
                            'layout' => 'admin-menu'
                        ],
                        'title' => 'Accordion'
                    ],
                    'button' => [
                        'icon' => '',
                        'route_name' => 'button',
                        'params' => [
                            'layout' => 'admin-menu'
                        ],
                        'title' => 'Button'
                    ],
                    'alert' => [
                        'icon' => '',
                        'route_name' => 'alert',
                        'params' => [
                            'layout' => 'admin-menu'
                        ],
                        'title' => 'Alert'
                    ],
                    'progress-bar' => [
                        'icon' => '',
                        'route_name' => 'progress-bar',
                        'params' => [
                            'layout' => 'admin-menu'
                        ],
                        'title' => 'Progress Bar'
                    ],
                    'tooltip' => [
                        'icon' => '',
                        'route_name' => 'tooltip',
                        'params' => [
                            'layout' => 'admin-menu'
                        ],
                        'title' => 'Tooltip'
                    ],
                    'dropdown' => [
                        'icon' => '',
                        'route_name' => 'dropdown',
                        'params' => [
                            'layout' => 'admin-menu'
                        ],
                        'title' => 'Dropdown'
                    ],
                    'typography' => [
                        'icon' => '',
                        'route_name' => 'typography',
                        'params' => [
                            'layout' => 'admin-menu'
                        ],
                        'title' => 'Typography'
                    ],
                    'icon' => [
                        'icon' => '',
                        'route_name' => 'icon',
                        'params' => [
                            'layout' => 'admin-menu'
                        ],
                        'title' => 'Icon'
                    ],
                    'loading-icon' => [
                        'icon' => '',
                        'route_name' => 'loading-icon',
                        'params' => [
                            'layout' => 'admin-menu'
                        ],
                        'title' => 'Loading Icon'
                    ]
                ]
            ],
            'forms' => [
                'icon' => 'sidebar',
                'title' => 'Forms',
                'sub_menu' => [
                    'regular-form' => [
                        'icon' => '',
                        'route_name' => 'regular-form',
                        'params' => [
                            'layout' => 'admin-menu'
                        ],
                        'title' => 'Regular Form'
                    ],
                    'datepicker' => [
                        'icon' => '',
                        'route_name' => 'datepicker',
                        'params' => [
                            'layout' => 'admin-menu'
                        ],
                        'title' => 'Datepicker'
                    ],
                    'tail-select' => [
                        'icon' => '',
                        'route_name' => 'tail-select',
                        'params' => [
                            'layout' => 'admin-menu'
                        ],
                        'title' => 'Tail Select'
                    ],
                    'file-upload' => [
                        'icon' => '',
                        'route_name' => 'file-upload',
                        'params' => [
                            'layout' => 'admin-menu'
                        ],
                        'title' => 'File Upload'
                    ],
                    'wysiwyg-editor' => [
                        'icon' => '',
                        'route_name' => 'wysiwyg-editor',
                        'params' => [
                            'layout' => 'admin-menu'
                        ],
                        'title' => 'Wysiwyg Editor'
                    ],
                    'validation' => [
                        'icon' => '',
                        'route_name' => 'validation',
                        'params' => [
                            'layout' => 'admin-menu'
                        ],
                        'title' => 'Validation'
                    ]
                ]
            ],
            'widgets' => [
                'icon' => 'hard-drive',
                'title' => 'Widgets',
                'sub_menu' => [
                    'chart' => [
                        'icon' => '',
                        'route_name' => 'chart',
                        'params' => [
                            'layout' => 'admin-menu'
                        ],
                        'title' => 'Chart'
                    ],
                    'slider' => [
                        'icon' => '',
                        'route_name' => 'slider',
                        'params' => [
                            'layout' => 'admin-menu'
                        ],
                        'title' => 'Slider'
                    ],
                    'image-zoom' => [
                        'icon' => '',
                        'route_name' => 'image-zoom',
                        'params' => [
                            'layout' => 'admin-menu'
                        ],
                        'title' => 'Image Zoom'
                    ]
                ]
            ]
        ];
    }
}
