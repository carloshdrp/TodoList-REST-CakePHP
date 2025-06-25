<?php
declare(strict_types=1);

namespace Api\Model\Entity;

use Cake\I18n\FrozenTime;
use Cake\ORM\Entity;

/**
 * TodoTask Entity
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @property FrozenTime $created
 * @property FrozenTime $modified
 */
class TodoTask extends Entity
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
        'title' => true,
        'description' => true,
        'created' => true,
        'modified' => true,
    ];
}
