<?php
declare(strict_types=1);

namespace Api\Model\Entity;

use Cake\I18n\FrozenTime;
use Cake\ORM\Entity;
use DateTime;

/**
 * AuthToken Entity
 *
 * @property int $id
 * @property string $token
 * @property int $user_id
 * @property FrozenTime $created
 * @property FrozenTime $modified
 *
 * @property AuthUser $auth_user
 */
class AuthToken extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array<string, bool>
     */
    protected $_accessible = [
        'token' => true,
        'user_id' => true,
        'created' => true,
        'modified' => true,
        'auth_user' => true,
        'expires' => true,
        'refresh' => true,
    ];

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * @var array<string>
     */
    protected $_hidden = [
        'token',
    ];

    public function isExpired(): bool
    {
        return $this->expires < new DateTime();
    }
}
