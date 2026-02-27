const stallService = require('../services/stallService');
exports.addStall = async(req, res)=>{
    try{
        const newStall = await stallService.registerStall(req.body);
        res.status(201).json({message: 'Stall registered successfully', stall: newStall});
    }catch(error){
        res.status(500).json({status: "fail", message:error.message});  
    }
};
exports.getStalls = async(req, res)=>{
    try{
        const stalls = await stallService.getStallsForFair(req.params.fair_id);
        res.status(200).json(stalls);
    }catch(error){
        res.status(500).json({message: error.message});
    }
};