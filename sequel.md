#### Find Duplicate Apply

```sql
SELECT ca1.id, bu1.fnama, bu1.email, ca1.b_lowongan_id, ca1.is_process, ca1.is_failed, ca1.is_active FROM b_user bu1 JOIN c_apply ca1 ON bu1.id = ca1.b_user_id WHERE bu1.id IN( SELECT ca.b_user_id FROM `c_apply` ca WHERE 1 GROUP BY ca.b_user_id HAVING COUNT(ca.b_user_id) > 1 ) ORDER BY bu1.fnama ASC, ca1.id ASC
```
//Add ttd in d_offer
ALTER TABLE `d_offer` ADD `ttd` VARCHAR(255) NOT NULL DEFAULT '' AFTER `cdate`;