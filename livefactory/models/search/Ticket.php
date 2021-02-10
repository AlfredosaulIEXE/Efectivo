<?php

namespace livefactory\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use livefactory\models\Ticket as TicketModel;

/**
 * Ticket represents the model behind the search form about `livefactory\models\Ticket`.
 */
class Ticket extends TicketModel
{
    public function rules()
    {
        return [
            [['id', 'ticket_type_id','ticket_id', 'ticket_priority_id', 'ticket_impact_id', 'queue_id', 'user_assigned_id', 'referenced_ticket_id', 'ticket_status_id', 'added_at', 'updated_at', 'created_by','ticket_category_id_2','department_id','ticket_category_id_1'], 'integer'],
            [['ticket_title', 'ticket_description', 'escalated_flag','due_date'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
		//print_r(Yii::$app->user->identity->entity_id);exit;
        //if(!empty($_GET['userassigned']))
       // {
            //$query = TicketModel::find()->joinWith('ticketStatus')->joinWith('ticketPriority')->orderBy('tbl_ticket_status.sort_order,tbl_ticket_priority.sort_order')->where('tbl_ticket.user_assigned_id = NULL');
            //echo $query;
       // }
		if(Yii::$app->params['HIDE_COMPLETED_TICKET_BY_DEFAULT'] =='Yes' && $_GET['Ticket']['ticket_status_id'] !=TicketStatus::_COMPLETED){
			if(!empty($_GET['sort'])){
				//$query = TicketModel::find()->where("ticket_status_id !=3");
				if(Yii::$app->params['user_role'] !='admin'){
					if(Yii::$app->user->identity->userType->type=="Customer") // User type is customer
					{
						$query = TicketModel::find()->where("ticket_status_id !=".TicketStatus::_COMPLETED." and ticket_customer_id = ".Yii::$app->user->identity->entity_id);
					}
					else // User type is not customer
					{
						$query = TicketModel::find()->where(" EXISTS(Select * FROM tbl_queue_users  WHERE queue_id =tbl_ticket.queue_id and user_id=".Yii::$app->user->identity->id.") and ticket_status_id !=".TicketStatus::_COMPLETED);
					}
				}
				else
				{
					$query = TicketModel::find()->where("ticket_status_id !=".TicketStatus::_COMPLETED);
				}
			}else{
			//$query = TicketModel::find()->joinWith('ticketStatus')->joinWith('ticketPriority')->orderBy('tbl_ticket_status.sort_order,tbl_ticket_priority.sort_order')->where("ticket_status_id !=3");
			if(Yii::$app->params['user_role'] !='admin'){
				if(Yii::$app->user->identity->userType->type=="Customer") // User type is customer
				{
					$query =TicketModel::find()->joinWith('ticketStatus')->joinWith('ticketPriority')->orderBy('tbl_ticket_status.sort_order,tbl_ticket_priority.sort_order')->where("ticket_status_id !=".TicketStatus::_COMPLETED." and ticket_customer_id = ".Yii::$app->user->identity->entity_id);
				}
				else // User type is not customer
				{
					$query = TicketModel::find()->joinWith('ticketStatus')->joinWith('ticketPriority')->orderBy('tbl_ticket_status.sort_order,tbl_ticket_priority.sort_order')->where(" EXISTS(Select *	FROM tbl_queue_users  WHERE queue_id =tbl_ticket.queue_id and user_id=".Yii::$app->user->identity->id.") and ticket_status_id !=".TicketStatus::_COMPLETED);
				}
			}
			else
			{
				$query = TicketModel::find()->joinWith('ticketStatus')->joinWith('ticketPriority')->orderBy('tbl_ticket_status.sort_order,tbl_ticket_priority.sort_order')->where("ticket_status_id !=".TicketStatus::_COMPLETED);
			}
		  }
		}else{
			if(!empty($_GET['sort'])){
				//$query = TicketModel::find();
				if(Yii::$app->params['user_role'] !='admin'){
					if(Yii::$app->user->identity->userType->type=="Customer") // User type is customer
					{
						$query = TicketModel::find()->where("ticket_customer_id = ".Yii::$app->user->identity->entity_id);
					}
					else // User type is not customer
					{
						$query = TicketModel::find()->where(" EXISTS(Select * FROM tbl_queue_users  WHERE queue_id =tbl_ticket.queue_id and user_id=".Yii::$app->user->identity->id.")");
					}
				}
				else
				{
					$query = TicketModel::find();
				}
			}else{
				    //$query = TicketModel::find()->joinWith('ticketStatus')->joinWith('ticketPriority')->orderBy('tbl_ticket_status.sort_order,tbl_ticket_priority.sort_order');
                if(Yii::$app->params['user_role'] !='admin'){
					if(Yii::$app->user->identity->userType->type=="Customer") // User type is customer
					{
						$query = TicketModel::find()->joinWith('ticketStatus')->joinWith('ticketPriority')->orderBy('tbl_ticket_status.sort_order,tbl_ticket_priority.sort_order')->where(" ticket_customer_id = ".Yii::$app->user->identity->entity_id);
					}
					else // User type is not customer
					{
						$query = TicketModel::find()->joinWith('ticketStatus')->joinWith('ticketPriority')->orderBy('tbl_ticket_status.sort_order,tbl_ticket_priority.sort_order')->where(" EXISTS(Select * FROM tbl_queue_users  WHERE queue_id =tbl_ticket.queue_id and user_id=".Yii::$app->user->identity->id.")");
					}
                }
				else
				{
					$query = TicketModel::find()->joinWith('ticketStatus')->joinWith('ticketPriority')->orderBy('tbl_ticket_status.sort_order,tbl_ticket_priority.sort_order');
				}
			}
		}
       /// $query = TicketModel::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'ticket_type_id' => $this->ticket_type_id,
            'ticket_priority_id' => $this->ticket_priority_id,
            'ticket_impact_id' => $this->ticket_impact_id,
			'ticket_category_id_1' => $this->ticket_category_id_1,
            'queue_id' => $this->queue_id,
            'user_assigned_id' => $this->user_assigned_id,
            'referenced_ticket_id' => $this->referenced_ticket_id,
            'ticket_status_id' => $this->ticket_status_id,
            'added_at' => $this->added_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
        ]);      
        if(!empty($_REQUEST['userassigned']))
        {
            //var_dump($_REQUEST['userassigned']);
            $query->andFilterWhere([
                'user_assigned_id' => ''
            ]);
        }  
        $query->andFilterWhere(['like', 'ticket_title', $this->ticket_title])
            ->andFilterWhere(['like', 'ticket_description', $this->ticket_description])
            ->andFilterWhere(['like', 'escalated_flag', $this->escalated_flag]);

        return $dataProvider;
    }
	public function searchMyTickets($params)
	{
		if(Yii::$app->params['HIDE_COMPLETED_TICKET_BY_DEFAULT'] =='Yes' && $_GET['Ticket']['ticket_status_id'] !=TicketStatus::_COMPLETED){
			if(!empty($_GET['sort'])){
				$query = TicketModel::find()->where("user_assigned_id=".Yii::$app->user->identity->id." and ticket_status_id !=".TicketStatus::_COMPLETED);
			}else{
			$query = TicketModel::find()->joinWith('ticketStatus')->joinWith('ticketPriority')->orderBy('tbl_ticket_status.sort_order,tbl_ticket_priority.sort_order')->where("user_assigned_id=".Yii::$app->user->identity->id." and ticket_status_id !=".TicketStatus::_COMPLETED);
			}
		}else{
			if(!empty($_GET['sort'])){
				$query = TicketModel::find()->where("user_assigned_id=".Yii::$app->user->identity->id);
			}else{
			$query = TicketModel::find()->joinWith('ticketStatus')->joinWith('ticketPriority')->orderBy('tbl_ticket_status.sort_order,tbl_ticket_priority.sort_order')->where("user_assigned_id=".Yii::$app->user->identity->id);
			}
		}

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'ticket_type_id' => $this->ticket_type_id,
            'ticket_priority_id' => $this->ticket_priority_id,
            'ticket_impact_id' => $this->ticket_impact_id,
			'ticket_category_id_1' => $this->ticket_category_id_1,
            'queue_id' => $this->queue_id,
            'user_assigned_id' => $this->user_assigned_id,
            'referenced_ticket_id' => $this->referenced_ticket_id,
            'ticket_status_id' => $this->ticket_status_id,
            'added_at' => $this->added_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
        ]);

        $query->andFilterWhere(['like', 'ticket_title', $this->ticket_title])
            ->andFilterWhere(['like', 'ticket_description', $this->ticket_description])
            ->andFilterWhere(['like', 'escalated_flag', $this->escalated_flag]);

        return $dataProvider;
	}
	 public function searchPendingTickets($params)
    {
		if(!empty($_GET['sort'])){
			$query = TicketModel::find()->where("ticket_status_id=".TicketStatus::_NEEDSACTION." or ticket_status_id=".TicketStatus::_INPROCESS." or ticket_status_id=".TicketStatus::_REOPENED);
		if(Yii::$app->params['user_role'] !='admin'){
			$query = TicketModel::find()->where("(ticket_status_id=".TicketStatus::_NEEDSACTION."  or ticket_status_id=".TicketStatus::_INPROCESS."  or ticket_status_id=".TicketStatus::_REOPENED.") and EXISTS(Select *
		FROM tbl_queue_users  WHERE queue_id =tbl_ticket.queue_id and user_id=".Yii::$app->user->identity->id.")");
		}
		}else{
			$query = TicketModel::find()->joinWith('ticketStatus')->joinWith('ticketPriority')->orderBy('tbl_ticket_status.sort_order,tbl_ticket_priority.sort_order')->where("ticket_status_id=".TicketStatus::_NEEDSACTION." or ticket_status_id=".TicketStatus::_INPROCESS." or ticket_status_id=".TicketStatus::_REOPENED);
			if(Yii::$app->params['user_role'] !='admin'){
				$query = TicketModel::find()->joinWith('ticketStatus')->joinWith('ticketPriority')->orderBy('tbl_ticket_status.sort_order,tbl_ticket_priority.sort_order')->where("(ticket_status_id=".TicketStatus::_NEEDSACTION." or ticket_status_id=".TicketStatus::_INPROCESS." or ticket_status_id=".TicketStatus::_REOPENED.") and EXISTS(Select *
		FROM tbl_queue_users  WHERE queue_id =tbl_ticket.queue_id and user_id=".Yii::$app->user->identity->id.")");
			}
		}
       /// $query = TicketModel::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'ticket_type_id' => $this->ticket_type_id,
            'ticket_priority_id' => $this->ticket_priority_id,
            'ticket_impact_id' => $this->ticket_impact_id,
			'ticket_category_id_1' => $this->ticket_category_id_1,
            'queue_id' => $this->queue_id,
            'user_assigned_id' => $this->user_assigned_id,
            'referenced_ticket_id' => $this->referenced_ticket_id,
            'ticket_status_id' => $this->ticket_status_id,
            'added_at' => $this->added_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
        ]);        
        $query->andFilterWhere(['like', 'ticket_title', $this->ticket_title])
            ->andFilterWhere(['like', 'ticket_description', $this->ticket_description])
            ->andFilterWhere(['like', 'escalated_flag', $this->escalated_flag]);

        return $dataProvider;
    }

	public function searchLinkedWithResolution($id)
    {
        $query = TicketModel::find()->where('id in (select ticket_id from tbl_resolution_reference where resolution_id='.$id.')');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        
		return $dataProvider;
    }
	
	public function searchTicketsWithQueueID($queue_id)
    {
        $query = TicketModel::find()->where(' queue_id='.$queue_id);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        
		return $dataProvider;
    }

	public function searchUnlinkedTickets($id)
    {
        $query = TicketModel::find()->where('ticket_status_id != '.TicketStatus::_CANCELLED.' and id not in (select ticket_id from	tbl_resolution_reference where resolution_id='.$id.')');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        
		return $dataProvider;
    }

	/*public function getCountResolved($id)
	{
		return (new TicketModel)->find()->where('ticket_resolution_id='.$id)->count();
	}*/

}
