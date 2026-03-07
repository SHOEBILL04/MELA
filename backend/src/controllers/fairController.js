const fairService = require('../services/fairService');

exports.getFairs = async(req , res)=>{
    try{
        const fairs = await fairService.fetchAllFairs();
        res.status(200).json(fairs);
    }catch(error)
    {
        res.status(500).json({message: error.message});
    }
};
exports.addFair = async(req, res)=>{
    try{
        await fairService.createNewFair(req.body);
        res.status(201).json({message: 'Fair created successfully'});  
    }catch(error){
        res.status(500).json({status: "fail", message:error.message});
    }
};

exports.updateFair = async(req, res)=>{
    try{
        const updated = await fairService.updateFair(req.params.id, req.body);
        if (!updated) return res.status(404).json({message: 'Fair not found'});
        res.status(200).json({message: 'Fair updated successfully'});  
    }catch(error){
        res.status(500).json({status: "fail", message:error.message});
    }
};

exports.deleteFair = async(req, res)=>{
    try{
        const deleted = await fairService.deleteFair(req.params.id);
        if (!deleted) return res.status(404).json({message: 'Fair not found'});
        res.status(200).json({message: 'Fair deleted successfully'});  
    }catch(error){
        res.status(500).json({message:error.message});
    }
};