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