<?php

namespace CodeDelivery\Http\Controllers\Api\Deliveryman;

use CodeDelivery\Repositories\OrderRepository;
use CodeDelivery\Repositories\UserRepository;
use CodeDelivery\Services\OrderService;
use Illuminate\Http\Request;

use CodeDelivery\Http\Requests;
use CodeDelivery\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use LucaDegasperi\OAuth2Server\Facades\Authorizer;

class DeliverymanCheckoutController extends Controller
{

    /**
     * @var OrderRepository
     */
    private $orderRepository;
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var OrderService
     */
    private $orderService;


    public function __construct(OrderRepository $orderRepository, UserRepository $userRepository, OrderService $orderService)
    {
        $this->orderRepository = $orderRepository;
        $this->userRepository = $userRepository;
        $this->orderService = $orderService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $id = Authorizer::getResourceOwnerId();

        //Buscando todas as ordens do deliveryman que estiver logado -> E incluir um with para acrencentar a relacao de itens na consulta
        $orders = $this->orderRepository->with(['items'])->scopeQuery(function($query) use($id){
            return $query->where('user_deliveryman_id', '=', $id);
        })->paginate();

        return $orders;
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $idDeliveryman = Authorizer::getResourceOwnerId();

        return $this->orderRepository->getByIdAndDeliveryman($id, $idDeliveryman);

    }

}